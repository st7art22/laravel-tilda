<?php

namespace IncOre\Tilda\Objects\Page;

use IncOre\Tilda\Objects\BaseObject;
use IncOre\Tilda\Objects\Asset;

/**
 * Class Page
 * @package IncOre\Tilda\Objects
 *
 * @property int $id
 * @property int $projectid
 * @property string $title
 * @property string $descr
 * @property string $img
 * @property string $featureimg
 * @property string $alias
 * @property string $date
 * @property int $sort
 * @property int timestamp $published
 * @property string $filename
 * @property string $html
 * @property Asset[] $images
 * @property Asset[] $css
 * @property Asset[] $js
 */
class ExportedPage extends BaseObject
{

}