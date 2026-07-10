<?php

namespace Content\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Content\App\Services\PageService;
use App\Http\Controllers\WebController;
class LayoutController extends WebController
{
    /**
     * Construct
     * @param PageService $pageService
     */
    public function __construct(public PageService $pageService)
    {
        parent::__construct();
        $this->middleware('userCanAny:developer:content-pages:full,developer:content-pages:view')->only('form');
        $this->middleware('userCanAny:developer:content-pages:full,developer:content-pages:update')->only('update');
    }

    /**
     * Form
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function form(Request $request)
    {
        if (!$request->has('layout')) {
            return redirect()->route('module.content.admin.layouts.schema', [
                'layout' => 'schema'
            ]);
        }

        $routes = resolveInertiaRoutes(config('menus.pages'));

        $layout = $request->input('layout');

        $content = $this->pageService->loadLayout($layout);

        return view('Content::admin.pages.layout', compact('content', 'layout', 'routes'));
    }


    /**
     * updated
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $path = $this->pageService->loadLayoutPath($request->input('layout'));

        $this->pageService->updateFile($path, $request->input('content'));

        return back()->with('status', __('Layout updated successfully'));
    }
}
