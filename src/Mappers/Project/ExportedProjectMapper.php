<?php

namespace IncOre\Tilda\Mappers\Project;

use IncOre\Tilda\Exceptions\Map\UnableToMapApiResponseException;
use IncOre\Tilda\Exceptions\InvalidJsonException;
use IncOre\Tilda\Mappers\MapperInterface;
use IncOre\Tilda\Mappers\ObjectMapper;
use IncOre\Tilda\Objects\Project\ExportedProject;

class ExportedProjectMapper extends ObjectMapper implements MapperInterface
{

    protected $attributes = [
        'id',
        'projectid',
        'title',
        'descr',
        'customdomain',
        'export_csspath',
        'export_jspath',
        'export_imgpath',
        'indexpageid',
        'page404id',
        'htaccess',
    ];

    protected $assets = [
        'images',
        'css',
        'js',
    ];

    /**
     * @param string $json
     * @return ExportedProject $project
     * @throws InvalidJsonException
     * @throws UnableToMapApiResponseException
     */
    public function map(string $json)
    {
        if (($project = json_decode($json, true)) === null) {
            throw new InvalidJsonException;
        }
        if (!isset($project['result']) || !is_array($project['result'])) {
            throw new UnableToMapApiResponseException("Invalid result field at api response");
        }
        return new ExportedProject($this->mapAttributes($project['result']));
    }

}
