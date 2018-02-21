<?php

namespace IncOre\Tilda\Mappers;

use IncOre\Tilda\Exceptions\Map\MapperNotFoundException;
use IncOre\Tilda\Mappers\Page\ExportedPageMapper;
use IncOre\Tilda\Mappers\Page\PageMapper;
use IncOre\Tilda\Mappers\Page\PagesListMapper;
use IncOre\Tilda\Mappers\Project\ExportedProjectMapper;
use IncOre\Tilda\Mappers\Project\ProjectMapper;
use IncOre\Tilda\Mappers\Project\ProjectsListMapper;

class MapperFactory
{

    /**
     * @param string $apiMethod
     * @return MapperInterface $mapper
     * @throws MapperNotFoundException
     */
    public static function create(string $apiMethod)
    {
        $mappers = [
            'getprojectslist' => ProjectsListMapper::class,
            'getproject' => ProjectMapper::class,
            'getprojectexport' => ExportedProjectMapper::class,
            'getpageslist' => PagesListMapper::class,
            'getpage' => PageMapper::class,
            'getpagefull' => PageMapper::class,
            'getpageexport' => ExportedPageMapper::class,
            'getpagefullexport' => ExportedPageMapper::class,
        ];
        if (isset($mappers[$apiMethod])) {
            return new $mappers[$apiMethod];
        }
        throw new MapperNotFoundException("Mapper for $apiMethod api method not found");
    }

}