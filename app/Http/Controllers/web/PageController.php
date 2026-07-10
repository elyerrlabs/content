<?php

namespace Content\App\Http\Controllers\web;

use App\Http\Controllers\WebController;
use Content\App\Services\PageService;

class PageController extends WebController
{

    /**
     * Contruct
     * @param PageService $pageService
     */
    public function __construct(protected PageService $pageService)
    {
    }

    /**
     * Summary of homePage
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function page(string $slug = '')
    {
        $page = $this->pageService->findPage($slug);

        return view()->file($page->path);
    }
}
