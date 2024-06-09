<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string test()
 *
 * @see \App\Libraries\OpenGraphImage
 */
class OpenGraphImage extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'open-graph-image';
    }
}
