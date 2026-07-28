<?php

namespace Content\App\Http\Controllers\Admin;

use App\Http\Controllers\WebController;
use Content\App\Services\SitemapService;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

final class SitemapController extends WebController
{

    /**
     * Construct
     */
    public function __construct(protected SitemapService $sitemapService)
    {
        parent::__construct();
        $this->middleware("userCanAny:developer:content-seo:full,developer:content-seo:view")->only('index', 'metaForm', 'robotForm');
        $this->middleware("userCanAny:developer:content-seo:full,developer:content-seo:create")->only('store', 'updateMetaForm', 'updateMeta', 'updateRobot');
        $this->middleware("userCanAny:developer:content-seo:full,developer:content-seo:destroy")->only('delete', 'deleteFavicon');
        $this->middleware("userCanAny:developer:content-seo:full,developer:content-seo:reset")->only('reset');

    }

    /**
     * List sitemap
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $content = $this->sitemapService->getOrUpdateCustomSitemap();

        return view('Content::admin.sitemap.index', compact('content'), [
            'routes' => resolveInertiaRoutes(config('menus.pages'))
        ]);
    }

    /**
     * Add new route
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateMeta(Request $request)
    {
        $this->sitemapService->getOrUpdateCustomSitemap($request->input('content'), true);

        return redirect()->back()->with("status", __('Sitemap updated succesfully'));
    }



    /**
     * Reset sitemap
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset()
    {
        $this->sitemapService->reset();
        return redirect()->back()->with("status", __('Sitemap reset successfully'));
    }

    /**
     * Robot form
     * @return \Illuminate\Contracts\View\View
     */
    public function robotForm()
    {
        $content = $this->sitemapService->getOrUpdateContent(
            'public/robots.txt',
            "User-agent: *\nDisallow: /"
        );

        return view('Content::admin.sitemap.robot', compact('content'), [
            'routes' => resolveInertiaRoutes(config('menus.pages'))
        ]);
    }

    /**
     * Updated robot.txt
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRobot(Request $request)
    {
        $this->validate($request, [
            'content' => 'required',
        ]);

        $this->sitemapService->getOrUpdateContent(
            'public/robots.txt',
            $request->input('content'),
            true,
            true
        );

        return redirect()->back()->with('status', __('Content updated successfully'));
    }

}
