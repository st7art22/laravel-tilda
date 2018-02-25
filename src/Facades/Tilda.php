<?php

namespace IncOre\Tilda\Facades;

use Illuminate\Support\Facades\Facade;
use IncOre\Tilda\TildaLoader;
use IncOre\Tilda\TildaApi;

/**
 * Class Tilda
 * @package IncOre\Tilda\Facades
 * @method static TildaLoader page
 * @method static TildaLoader assets
 * @method static TildaApi getProjectsList
 * @method static TildaApi getProject
 * @method static TildaApi getProjectExport
 * @method static TildaApi getPagesList
 * @method static TildaApi getPage
 * @method static TildaApi getPageFull
 * @method static TildaApi getPageExport
 * @method static TildaApi getPageFullExport
 */
class Tilda extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'tilda';
    }

}
