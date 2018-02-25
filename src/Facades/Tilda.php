<?php

namespace IncOre\Tilda\Facades;

use Illuminate\Support\Facades\Facade;
use IncOre\Tilda\TildaLoader;
use IncOre\Tilda\TildaApi;

/**
 * Class Tilda
 * @package IncOre\Tilda\Facades
 * @method TildaLoader page
 * @method TildaLoader assets
 * @method TildaApi getProjectsList
 * @method TildaApi getProject
 * @method TildaApi getProjectExport
 * @method TildaApi getPagesList
 * @method TildaApi getPage
 * @method TildaApi getPageFull
 * @method TildaApi getPageExport
 * @method TildaApi getPageFullExport
 */
class Tilda extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'tilda';
    }

}
