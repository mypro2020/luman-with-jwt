<?php

namespace App\Services;

class DocumentService
{
    /**
     * The base uri to consume authors service
     * @var string
     */
    public $baseUri;
    public $uploadDir;

    /**
     * Authorization secret to pass to author api
     * @var string
     */

    public function __construct()
    {
        $this->baseUri = config('services.document.base_uri');
        $this->uploadDir = config('services.document.upload_path');
    }

    /**
     * upload document 
     */
    public function uploadDocument($file, $name)
    {
        $file->move($this->baseUri . $this->uploadDir, $name);
        return $this->baseUri . $this->uploadDir . $name;
    }

    public function getImage($name)
    {

    }

}
