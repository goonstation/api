<?php

namespace App\Libraries\GameBridge;

class BridgeConnectionResponse
{
    public string|float $message = '';

    public bool $error = false;

    public bool $cached = false;

    public function __construct(string|float $message, bool $error = false, bool $cached = false)
    {
        $this->message = is_numeric($message) ? (float) $message : $message;
        $this->error = $error;
        $this->cached = $cached;
    }
}
