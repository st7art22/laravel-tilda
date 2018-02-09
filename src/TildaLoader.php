<?php

namespace IncOre\Tilda;

class TildaLoader
{
    private $client;
    private $config;

    public function __construct(TildaClient $client, array $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    public function page($pageId)
    {
        $pageInfo = $this->client->pageExport($pageId);
        if ($pageInfo) {
            $cssList = $pageInfo->css;
            $jsList = $pageInfo->js;
            $imgList = $pageInfo->images;
            $css = $this->load($cssList, $this->config['css']);
            $js = $this->load($jsList, $this->config['js']);
            $img = $this->load($imgList, $this->config['img']);
            if ($css && $js && $img) {
                return $pageInfo;
            }
        }
        return null;
    }

    public function assets($page)
    {
        if (!isset($page->css) || !isset($page->css)) {
            return null;
        }
        $cssList = $page->css;
        $jsList = $page->js;
        $files = [];
        $cssPublicPos = strpos($this->config['css'], 'public');
        $jsPublicPos = strpos($this->config['js'], 'public');
        if (!$cssPublicPos || !$jsPublicPos) {
            return null;
        }
        // string length of 'public'
        $publicLength = 6;
        $cssPath = substr($this->config['css'], $cssPublicPos + $publicLength) . '/';
        $jsPath = substr($this->config['js'], $jsPublicPos + $publicLength) . '/';
        foreach ($cssList as $file) {
            $files['css'][] = $cssPath . $file->to;
        }
        foreach ($jsList as $file) {
            $files['js'][] = $jsPath . $file->to;
        }
        return (array)$files;
    }

    private function load($fileList, $path)
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

    private function store($file, $assetPath, $localFilePath)
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

    private function isDirExists($path)
    {
        return file_exists($path) && is_dir($path);
    }

    private function createDir($path)
    {
        return mkdir($path, 0775, true);
    }
}