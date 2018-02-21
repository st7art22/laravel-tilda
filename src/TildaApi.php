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
     * @return ProjectsListItem[] $projectsList
     * @throws TildaApiException
     */
    public function getProjectsList()
    {
        return $this->request('getprojectslist');
    }

    /**
     * @return Project $project
     * @throws TildaApiException
     */
    public function getProject($projectId)
    {
        return $this->request('getproject', ['projectid' => $projectId]);
    }

    /**
     * @return ExportedProject $project
     * @throws TildaApiException
     */
    public function getProjectExport($projectId)
    {
        return $this->request('getprojectexport', ['projectid' => $projectId]);
    }

    /**
     * @return PagesListItem[] $pagesList
     * @throws TildaApiException
     */
    public function getPagesList($projectId)
    {
        return $this->request('getpageslist', ['projectid' => $projectId]);
    }

    /**
     * @return Page $page
     * @throws TildaApiException
     */
    public function getPage($pageId)
    {
        return $this->request('getpage', ['pageid' => $pageId]);
    }

    /**
     * @return Page $page
     * @throws TildaApiException
     */
    public function getPageFull($pageId)
    {
        return $this->request('getpagefull', ['pageid' => $pageId]);
    }

    /**
     * @return ExportedPage $page
     * @throws TildaApiException
     */
    public function getPageExport($pageId)
    {
        return $this->request('getpageexport', ['pageid' => $pageId]);
    }

    /**
     * @return ExportedPage $page
     * @throws TildaApiException
     */
    public function getPageFullExport($pageId)
    {
        return $this->request('getpagefullexport', ['pageid' => $pageId]);
    }

    protected function request($uri, $params = [])
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