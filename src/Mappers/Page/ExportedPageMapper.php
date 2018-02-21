<?php

namespace IncOre\Tilda\Mappers\Page;

use IncOre\Tilda\Exceptions\Map\UnableToMapApiResponseException;
use IncOre\Tilda\Exceptions\InvalidJsonException;
use IncOre\Tilda\Mappers\MapperInterface;
use IncOre\Tilda\Mappers\ObjectMapper;
use IncOre\Tilda\Objects\Page\ExportedPage;

class ExportedPageMapper extends ObjectMapper implements MapperInterface
{

    protected $attributes = [
        'id',
        'projectid',
        'title',
        'descr',
        'img',
        'featureimg',
        'alias',
        'date',
        'sort',
        'published',
        'filename',
        'html',
    ];

    protected $assets = [
        'images',
        'css',
        'js',
    ];
    /**
     * @param string $json
     * @return ExportedPage $page
     * @throws InvalidJsonException
     * @throws UnableToMapApiResponseException
     */
    public function map(string $json)
    {
        if (($page = json_decode($json, true)) === null) {
            throw new InvalidJsonException;
        }
        if (!isset($page['result']) || !is_array($page['result'])) {
            throw new UnableToMapApiResponseException("Invalid result field at api response");
        }
        return new ExportedPage($this->mapAttributes($page['result']));
    }

}