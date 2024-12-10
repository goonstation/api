<?php

namespace App\Libraries\GameBridge;

use App\Models\GameServer;
use Illuminate\Database\Eloquent\Collection as ModelCollection;
use Illuminate\Support\Collection;
use Laravel\Octane\Exceptions\TaskTimeoutException;
use Laravel\Octane\Facades\Octane;
use RuntimeException;

class BridgeConnection
{
    private Collection $targets;

    private int $timeout = 5;

    private ?string $message = null;

    private bool $force = false;

    private int $cacheFor = 30;

    public function target($target)
    {
        if (! $target) {
            throw new RuntimeException('Invalid target');
        }

        $this->targets = collect();
        $servers = new ModelCollection;

        if ($target instanceof GameServer) {
            $servers->add($target);
        } elseif ($target instanceof ModelCollection) {
            $servers = $target;
        } else {
            $query = GameServer::select(['server_id', 'address', 'port']);

            if (is_string($target)) {
                if ($target === 'active') {
                    $query = $query->where('active', true);
                } elseif ($target !== 'all') {
                    $query = $query->where('server_id', $target);
                }
            } elseif (is_array($target)) {
                $query = $query->whereIn('server_id', $target);
            }

            $servers = $query->get();
        }

        /** @var GameServer $server */
        foreach ($servers as $server) {
            $this->targets->add((object) [
                'serverId' => $server->server_id,
                'address' => $server->address,
                'port' => $server->port,
            ]);
        }

        return $this;
    }

    public function timeout(int $timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function message(string|array $message)
    {
        if (is_array($message)) {
            $message = http_build_query($message);
        }
        if (! str_starts_with($message, '?')) {
            $message = "?$message";
        }
        $this->message = $message;

        return $this;
    }

    public function force(bool $force)
    {
        $this->force = $force;

        return $this;
    }

    public function cacheFor(int $seconds)
    {
        $this->cacheFor = $seconds;

        return $this;
    }

    private function handler($address, $port, $options)
    {
        return function () use ($address, $port, $options) {
            $socket = new BridgeConnectionSocket($address, $port, $options);
            $response = '';
            $error = false;

            try {
                $socket->send();
                if ($socket->wantResponse) {
                    $response = $socket->read();
                    $error = $socket->error;
                }
            } catch (\Throwable $e) {
                if ($socket->wantResponse) {
                    $response = $e->getMessage();
                    $error = true;
                }
            }

            $socket->disconnect();

            return new BridgeConnectionResponse($response, $error, $socket->cacheHit);
        };
    }

    public function send(bool $wantResponse = true): BridgeConnectionResponse|Collection
    {
        $options = (object) [
            'message' => $this->message,
            'timeout' => $this->timeout,
            'force' => $this->force,
            'cacheFor' => $this->cacheFor,
            'wantResponse' => $wantResponse,
        ];

        $jobs = [];
        $timeout = 0;
        foreach ($this->targets as $target) {
            $jobs[$target->serverId] = $this->handler($target->address, $target->port, $options);
            if ($wantResponse) {
                $timeout += $this->timeout * 3; // connect, send, read
            } else {
                $timeout += $this->timeout * 2;  // connect, send
            }
        }

        $response = null;
        try {
            $responses = collect(Octane::concurrently($jobs, $timeout * 1000));
            $response = count($responses) === 1 ? $responses->first() : $responses;
        } catch (TaskTimeoutException $e) {
            $response = new BridgeConnectionResponse($e->getMessage(), true);
        }

        return $response;
    }

    public function sendAndForget()
    {
        $this->send(false);
    }
}
