<?php

namespace App\Libraries\GameBridge;

use App\Models\GameServer;
use Illuminate\Database\Eloquent\Collection as ModelCollection;
use Illuminate\Support\Collection;
use RuntimeException;

class BridgeConnection
{
    private array $targets = [];

    private ?int $timeout = null;

    private ?string $message = null;

    private ?bool $force = null;

    private ?int $cacheFor = null;

    public function target($target)
    {
        if (! $target) {
            throw new RuntimeException('Invalid target');
        }

        $this->targets = [];
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
            $this->targets[] = (object) [
                'serverId' => $server->server_id,
                'socket' => new BridgeConnectionSocket(
                    $server->address,
                    $server->port,
                    (object) [
                        'message' => $this->message,
                        'timeout' => $this->timeout,
                        'force' => $this->force,
                        'cacheFor' => $this->cacheFor,
                    ]
                ),
            ];
        }

        return $this;
    }

    private function updateTarget($key, $val)
    {
        foreach ($this->targets as $target) {
            if (! method_exists($target->socket, $key)) {
                continue;
            }
            $target->socket->$key($val);
        }
        $this->$key = $val;
    }

    public function timeout(int $timeout)
    {
        $this->updateTarget('timeout', $timeout);

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
        $this->updateTarget('message', $message);

        return $this;
    }

    public function force(bool $force)
    {
        $this->updateTarget('force', $force);

        return $this;
    }

    public function cacheFor(int $seconds)
    {
        $this->updateTarget('cacheFor', $seconds);

        return $this;
    }

    public function send(bool $wantResponse = true): BridgeConnectionResponse|Collection
    {
        $responses = collect();
        foreach ($this->targets as $target) {
            $response = '';
            $error = false;
            try {
                $target->socket->wantResponse = $wantResponse;
                $target->socket->send();
                if ($wantResponse) {
                    $response = $target->socket->read();
                    $error = $target->socket->error;
                }
            } catch (\Throwable $e) {
                if ($wantResponse) {
                    $response = $e->getMessage();
                    $error = true;
                }
            }
            $target->socket->disconnect();
            if ($wantResponse) {
                $responses->add(new BridgeConnectionResponse(
                    $response,
                    $error,
                    $target->socket->cacheHit
                ));
            }
        }

        return count($responses) === 1 ? $responses->first() : $responses;
    }

    public function sendAndForget()
    {
        $this->send(false);
    }
}
