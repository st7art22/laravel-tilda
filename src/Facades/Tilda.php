<?php

namespace IncOre\Tilda\Facades;

use Illuminate\Support\Facades\Facade;

class Tilda extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'tilda';
    }

}
