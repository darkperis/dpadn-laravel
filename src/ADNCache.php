<?php

namespace Darkpony\ADNCache;

use Illuminate\Support\Facades\Facade;

class ADNCache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return EdgeportCache::class;
    }
}
