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
     * Render pages
     * @param string $locale
     * @param string $slug
     * @return \Illuminate\Contracts\View\View
     */
    public function page(string $locale = 'en', string $slug = '')
    {
       // dd($locale, $slug);
        return $this->pageService->renderPages($locale, $slug);
    }
}
