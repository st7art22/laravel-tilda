<?php

namespace IncOre\Tilda\Mappers\Page;

use IncOre\Tilda\Exceptions\Map\UnableToMapApiResponseException;
use IncOre\Tilda\Exceptions\InvalidJsonException;
use IncOre\Tilda\Mappers\MapperInterface;
use IncOre\Tilda\Mappers\ObjectMapper;
use IncOre\Tilda\Objects\Page\Page;

class PageMapper extends ObjectMapper implements MapperInterface
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

    /**
     * @param string $json
     * @return Page $page
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
        return new Page($this->mapAttributes($page['result']));
    }

}