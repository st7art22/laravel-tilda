<?php

namespace IncOre\Tilda;

use BadMethodCallException;
use IncOre\Tilda\Exceptions\Loader\TildaLoaderInvalidConfigurationException;
use IncOre\Tilda\Objects\Page\ExportedPage;

class TildaLoader
{
    protected $client;

    public function __construct(TildaApi $client)
    {
        $this->client = $client;
    }

    /**
     * @param int $pageId
     * @return Objects\Page\ExportedPage|null
     */
    public function page(int $pageId)
    {
        $pageInfo = $this->client->getPageExport($pageId);
        if ($pageInfo) {
            $cssList = $pageInfo->css;
            $jsList = $pageInfo->js;
            $imgList = $pageInfo->images;
            $css = $this->load($cssList, config('tilda.path.css'));
            $js = $this->load($jsList, config('tilda.path.js'));
            $img = $this->load($imgList, config('tilda.path.img'));
            if ($css && $js && $img) {
                return $pageInfo;
            }
        }
        return null;
    }

    /**
     * @param ExportedPage $page
     * @return array
     */
    public function assets(ExportedPage $page)
    {
        if (!$page->css || $page->js) {
            return null;
        }
        $cssList = $page->css;
        $jsList = $page->js;
        $files = [];
        $cssPath = substr(config('tilda.path.css'), strlen(public_path()));
        $jsPath = substr(config('tilda.path.js'), strlen(public_path()));
        foreach ($cssList as $file) {
            $files['css'][] = $cssPath . '/' . $file->to;
        }
        foreach ($jsList as $file) {
            $files['js'][] = $jsPath . '/' . $file->to;
        }
        return $files;
    }

    protected function load($fileList, $path)
    {
        foreach ($fileList as $file) {
            if (!$loaded = file_get_contents($file->from)) {
                return false;
            }
            if (!$this->store($loaded, $path, $file->to)) {
                return false;
            }
        }
        return true;
    }

    protected function store($file, $assetPath, $localFilePath)
    {
        if (!$this->isDirExists($assetPath)) {
            if (!$this->createDir($assetPath)) {
                return false;
            }
        }
        if ($assetPath[strlen($assetPath) - 1] !== '/') {
            $assetPath .= '/';
        }
        return file_put_contents($assetPath . $localFilePath, $file);
    }

    protected function isDirExists($path)
    {
        return file_exists($path) && is_dir($path);
    }

    protected function createDir($path)
    {
        return mkdir($path, 0775, true);
    }

    protected function validateConfig()
    {
        foreach (config('tilda.path') as $param) {
            if (!$param || !is_dir($param)) {
                throw new TildaLoaderInvalidConfigurationException;
            }
        }
    }

    public function __call($method, $arguments)
    {
        if (!method_exists($this->client, $method)) {
            throw new BadMethodCallException;
        }
        return call_user_func_array([$this->client, $method], $arguments);
    }
}