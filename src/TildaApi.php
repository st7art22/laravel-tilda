<?php

namespace IncOre\Tilda;

use IncOre\Tilda\Exceptions\Api\HttpClientExceptions;
use IncOre\Tilda\Exceptions\Api\TildaApiConnectionException;
use IncOre\Tilda\Exceptions\Api\TildaApiErrorResponseException;
use IncOre\Tilda\Exceptions\Api\TildaApiException;
use IncOre\Tilda\Exceptions\Api\TildaApiInvalidConfigurationException;
use IncOre\Tilda\Exceptions\InvalidJsonException;
use IncOre\Tilda\Mappers\MapperFactory;
use IncOre\Tilda\Objects\Page\ExportedPage;
use IncOre\Tilda\Objects\Page\Page;
use IncOre\Tilda\Objects\Page\PagesListItem;
use IncOre\Tilda\Objects\Project\ExportedProject;
use IncOre\Tilda\Objects\Project\Project;
use IncOre\Tilda\Objects\Project\ProjectsListItem;

class TildaApi
{
    /**
     * @param bool $asJson
     * @return ProjectsListItem[] $projectsList
     * @throws TildaApiException
     */
    public function getProjectsList($asJson = false)
    {
        return $this->request('getprojectslist', [], $asJson);
    }

    /**
     * @param int $projectId
     * @param bool $asJson
     * @return Project $project
     * @throws TildaApiException
     */
    public function getProject(int $projectId, $asJson = false)
    {
        return $this->request('getproject', ['projectid' => $projectId], $asJson);
    }

    /**
     * @param int $projectId
     * @param bool $asJson
     * @return ExportedProject $project
     * @throws TildaApiException
     */
    public function getProjectExport(int $projectId, $asJson = false)
    {
        return $this->request('getprojectexport', ['projectid' => $projectId], $asJson);
    }

    /**
     * @param int $projectId
     * @param bool $asJson
     * @return PagesListItem[] $pagesList
     * @throws TildaApiException
     */
    public function getPagesList(int $projectId, $asJson = false)
    {
        return $this->request('getpageslist', ['projectid' => $projectId], $asJson);
    }

    /**
     * @param int $pageId
     * @param bool $asJson
     * @return Page $page
     * @throws TildaApiException
     */
    public function getPage(int $pageId, $asJson = false)
    {
        return $this->request('getpage', ['pageid' => $pageId], $asJson);
    }

    /**
     * @param int $pageId
     * @param bool $asJson
     * @return Page $page
     * @throws TildaApiException
     */
    public function getPageFull(int $pageId, $asJson = false)
    {
        return $this->request('getpagefull', ['pageid' => $pageId], $asJson);
    }

    /**
     * @param int $pageId
     * @param bool $asJson
     * @return ExportedPage $page
     * @throws TildaApiException
     */
    public function getPageExport(int $pageId, $asJson = false)
    {
        return $this->request('getpageexport', ['pageid' => $pageId], $asJson);
    }

    /**
     * @param int $pageId
     * @param bool $asJson
     * @return ExportedPage $page
     * @throws TildaApiException
     */
    public function getPageFullExport(int $pageId, $asJson = false)
    {
        return $this->request('getpagefullexport', ['pageid' => $pageId], $asJson);
    }

    protected function request($uri, $params = [], $asJson = false)
    {
        $this->validateConfig();
        $url = config('tilda.url.api_url') . '/' . config('tilda.url.api_ver') . '/' . $uri . $this->queryString($params);
        if ($curl = curl_init()) {
            $headers = [
                'Content-type: application/json',
                'Accept: application/json'
            ];
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            if (!($data = curl_exec($curl))) {
                throw new TildaApiConnectionException(curl_error($curl));
            }
            if ($asJson) {
                return $data;
            }
            if (($decoded = json_decode($data)) === null) {
                throw new InvalidJsonException;
            }
            if ($decoded->status != 'FOUND') {
                throw new TildaApiErrorResponseException($decoded->message);
            }
            curl_close($curl);
            return MapperFactory::create($uri)->map($data);
        }
        throw new HttpClientExceptions('Unable to init curl');
    }

    protected function queryString($params = [])
    {
        $accessParams = [
            'publickey' => config('tilda.url.public_key'),
            'secretkey' => config('tilda.url.secret_key')
        ];
        return '?' . http_build_query(array_merge($params, $accessParams));
    }

    protected function validateConfig()
    {
        foreach (config('tilda.url') as $param) {
            if (!$param) {
                throw new TildaApiInvalidConfigurationException;
            }
        }
    }
}