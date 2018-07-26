<?php

namespace D3jn\LaravelLanguages\Facades;

use Illuminate\Support\Facades\Facade;

class Languages extends Facade
{
    /**
     * Get facade accessor.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'languages';
    }
}
