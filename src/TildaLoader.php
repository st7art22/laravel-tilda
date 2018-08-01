<?php

namespace IncOre\Tilda;

use BadMethodCallException;
use IncOre\Tilda\Exceptions\Loader\AssetLoadingException;
use IncOre\Tilda\Exceptions\Loader\AssetStoringException;
use IncOre\Tilda\Exceptions\Loader\PageHasNoAssetsException;
use IncOre\Tilda\Exceptions\Loader\PageNotLoadedException;
use IncOre\Tilda\Exceptions\Loader\TildaLoaderInvalidConfigurationException;
use IncOre\Tilda\Objects\Page\ExportedPage;

// TODO: phpdoc update
// TODO: docs
// TODO: test

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
     * @throws PageNotLoadedException
     * @throws AssetLoadingException
     * @throws AssetStoringException
     */
    public function page(int $pageId)
    {
        $pageInfo = $this->client->getPageExport($pageId);
        if (!$pageInfo) {
            throw new PageNotLoadedException;
        }
        $cssList = $pageInfo->css;
        $jsList = $pageInfo->js;
        $imgList = $pageInfo->images;

        $cssList = !empty($cssList) ? $cssList : [];
        $jsList = !empty($jsList) ? $jsList : [];
        $imgList = !empty($imgList) ? $imgList : [];
        $this->load($cssList, config('tilda.path.css') . '/' . $pageId);
        $this->load($jsList, config('tilda.path.js') . '/' . $pageId);
        $this->load($imgList, config('tilda.path.img') . '/' . $pageId);
        return $pageInfo;
    }

    /**
     * @param ExportedPage $page
     * @return array
     * @throws PageHasNoAssetsException
     */
    public function assets(ExportedPage $page)
    {
        if (!$page->css || !$page->js) {
            throw new PageHasNoAssetsException;
        }
        $cssList = $page->css;
        $jsList = $page->js;
        $files = [];
        $cssPath = substr(config('tilda.path.css'), strlen(public_path()));
        $jsPath = substr(config('tilda.path.js'), strlen(public_path()));
        foreach ($cssList as $file) {
            $files['css'][] = $cssPath . '/' . $page->id . '/' . $file->to;
        }
        foreach ($jsList as $file) {
            $files['js'][] = $jsPath . '/' . $page->id . '/' . $file->to;
        }
        return $files;
    }

    protected function load($fileList, $path)
    {
        foreach ($fileList as $file) {
            $loaded = file_get_contents($file->from);
            if (!$loaded) {
                throw new AssetLoadingException('Unable to load ' . $file->from);
            }
            $this->store($loaded, $path, $file->to);
        }
    }

    protected function store($file, $assetPath, $localFilePath)
    {
        if (!$this->isDirExists($assetPath)) {
            if (!$this->createDir($assetPath)) {
                throw new AssetStoringException('Unable to create assets storage directory at ' . $assetPath);
            }
        }
        if ($assetPath[strlen($assetPath) - 1] !== '/') {
            $assetPath .= '/';
        }
        if (!file_put_contents($assetPath . $localFilePath, $file)) {
            throw new AssetStoringException('Unable to store asset to ' . $assetPath . $localFilePath);
        }
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
