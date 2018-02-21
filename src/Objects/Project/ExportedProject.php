<?php

namespace IncOre\Tilda\Objects\Project;

use IncOre\Tilda\Objects\BaseObject;
use IncOre\Tilda\Objects\Asset;

/**
 * Class Project
 * @package IncOre\Tilda\Objects
 *
 * @property int $id
 * @property  string $title
 * @property string $descr
 * @property string $customdomain
 * @property string $export_csspath
 * @property string $export_jspath
 * @property string $export_imgpath
 * @property int $indexpageid
 * @property string $customcssfile
 * @property string $favicon
 * @property int $page404id
 * @property string $htaccess
 * @property Asset[] $images
 * @property Asset[] $css
 * @property Asset[] $js
 */
class ExportedProject extends BaseObject
{

}