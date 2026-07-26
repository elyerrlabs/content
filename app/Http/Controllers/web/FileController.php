<?php
namespace Content\App\Http\Controllers\web;

use App\Http\Controllers\WebController;
use Content\App\Services\FileService;

class FileController extends WebController
{

    public function __construct(protected FileService $fileService)
    {
    }

    public function renderFile(string $id)
    {
         
        return $this->fileService->renderFile($id);
    }
}
