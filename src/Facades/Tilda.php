<?php

namespace IncOre\Tilda\Facades;

use Illuminate\Support\Facades\Facade;
use IncOre\Tilda\Objects\Page\ExportedPage;
use IncOre\Tilda\Objects\Page\Page;
use IncOre\Tilda\Objects\Page\PagesListItem;
use IncOre\Tilda\Objects\Project\ExportedProject;
use IncOre\Tilda\Objects\Project\Project;
use IncOre\Tilda\Objects\Project\ProjectsListItem;

/**
 * Class Tilda
 * @package IncOre\Tilda\Facades
 * @method static ExportedPage page(int $pageId)
 * @method static array assets(ExportedPage $page)
 * @method static ProjectsListItem[] getProjectsList
 * @method static Project getProject(int $projectId, bool $asJson = false)
 * @method static ExportedProject getProjectExport(int $projectId, bool $asJson = false)
 * @method static PagesListItem[] getPagesList(int $projectId, bool $asJson = false)
 * @method static Page getPage(int $pageId, bool $asJson = false)
 * @method static Page getPageFull(int $pageId, bool $asJson = false)
 * @method static ExportedPage getPageExport(int $pageId, bool $asJson = false)
 * @method static ExportedPage getPageFullExport(int $pageId, bool $asJson = false)
 */
class Tilda extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'tilda';
    }

}
