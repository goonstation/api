<?php

namespace App\Facades;

use App\Libraries\GameBridge\GameBridge as GameBridgeLibrary;
use Illuminate\Support\Facades\Facade;

/**
 * @see GameBridgeLibrary
 */
class GameBridge extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return GameBridgeLibrary::class;
    }
}
