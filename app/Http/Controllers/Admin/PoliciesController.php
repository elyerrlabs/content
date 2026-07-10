<?php

namespace Content\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Content\App\Services\PageService;
class PoliciesController extends LayoutController
{
    /**
     * Construct
     * @param PageService $pageService
     */
    public function __construct(public PageService $pageService)
    {
        parent::__construct($pageService);
    }

    /**
     * Form
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function form(Request $request)
    {
        if (!$request->has('layout')) {
            return redirect()->route('module.content.admin.policies.schema', [
                'layout' => 'terms-and-conditions'
            ]);
        }

        $routes = resolveInertiaRoutes(config('menus.pages'));

        $layout = $request->input('layout');

        $content = $this->pageService->loadLayout($layout);

        return view('Content::admin.pages.policies', compact('content', 'layout', 'routes'));
    }
}
