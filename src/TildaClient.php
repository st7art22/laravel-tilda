<?php

namespace IncOre\Tilda;

class TildaClient
{
    private $config;

    function __construct(array $config)
    {
        $this->config = $config;
    }

    public function projectsList()
    {
        $uri = '/getprojectslist';
        return $this->request($this->config['api_url'] . $this->config['api_ver'] . $uri . $this->buildUrlParams());
    }

    public function projects($projectId)
    {
        $uri = '/getproject';
        return $this->request($this->config['api_url'] . $this->config['api_ver'] . $uri . $this->buildUrlParams(['projectid' => $projectId]));
    }

    public function projectExport($projectId)
    {
        $uri = '/getprojectexport';
        return $this->request($this->config['api_url'] . $this->config['api_ver'] . $uri . $this->buildUrlParams(['projectid' => $projectId]));
    }

    public function pagesList($projectId)
    {
        $uri = '/getpageslist';
        return $this->request($this->config['api_url'] . $this->config['api_ver'] . $uri . $this->buildUrlParams(['projectid' => $projectId]));
    }

    public function page($pageId)
    {
        $uri = '/getpage';
        return $this->request($this->config['api_url'] . $this->config['api_ver'] . $uri . $this->buildUrlParams(['pageid' => $pageId]));
    }

    public function pageFull($pageId)
    {
        $uri = '/getpagefull';
        return $this->request($this->config['api_url'] . $this->config['api_ver'] . $uri . $this->buildUrlParams(['pageid' => $pageId]));
    }

    public function pageExport($pageId)
    {
        $uri = '/getpageexport';
        return $this->request($this->config['api_url'] . $this->config['api_ver'] . $uri . $this->buildUrlParams(['pageid' => $pageId]));
    }

    public function pageFullExport($pageId)
    {
        $uri = '/getpagefullexport';
        return $this->request($this->config['api_url'] . $this->config['api_ver'] . $uri . $this->buildUrlParams(['pageid' => $pageId]));
    }

    private function request($url)
    {
        if ($curl = curl_init()) {
            $headers = [
                'Content-type: application/json',
                'Accept: application/json'
            ];
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            if ($data = curl_exec($curl)) {
                $data = json_decode($data);
                if ($data->status == 'FOUND') {
                    return $data->result;
                }
            }
            curl_close($curl);
        }
        return false;
    }

    private function buildUrlParams($params = false)
    {
        $baseUrlParams = '?publickey=' . $this->config['public_key'] . '&secretkey=' . $this->config['secret_key'];
        if ($params && is_array($params)) {
            $customUrlParams = http_build_query($params);
            return $baseUrlParams . $customUrlParams;
        }
        return $baseUrlParams;
    }
}