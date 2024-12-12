<?php

namespace App\Libraries\GameBridge;

class GameBridge
{
    // private $connections = [];

    public function __construct()
    {
        //
    }

    public function create()
    {
        $connection = new BridgeConnection;

        // $this->connections[] = $connection;
        return $connection;
    }
}
