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
    public function __construct(protected SitemapService $SitemapService)
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
        $data = $this->SitemapService->listRoutes()->toArray();

        return view('Content::admin.sitemap.index', compact('data'), [
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
        $request->validate([
            'url' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_URL)) {
                        return $fail("The $attribute must be a valid URL.");
                    }

                    $scheme = parse_url($value, PHP_URL_SCHEME);

                    if (!in_array($scheme, ['http', 'https', 'ftp'])) {
                        return $fail(__("Only http, https or ftp protocols are allowed."));
                    }
                }
            ],
            'image' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
                        return $fail("The $attribute must be a valid URL.");
                    }
                }
            ],
            'changefreq' => [
                'nullable',
                Rule::in(['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'])
            ],
            'priority' => [
                'nullable',
                'numeric',
                'between:0.1,1.0'
            ],
        ]);

        $this->SitemapService->register(
            'pages',
            $request->url,
            $request->image,
            $request->changefreq ?? 'weekly',
            $request->priority ?? 0.5
        );

        return redirect()->back()->with("status", __('Sitemap updated succesfully'));
    }

    /**
     * Destroy
     * @param string $url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(string $url)
    {
        $this->SitemapService->remove($url);

        return redirect()->back()->with("status", __('Page deleted succesfully'));
    }

    /**
     * Reset sitemap
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset()
    {
        $this->SitemapService->reset();
        return redirect()->back()->with("status", __('Sitemap reset successfully'));
        ;
    }

    /**
     * Robot form
     * @return \Illuminate\Contracts\View\View
     */
    public function robotForm()
    {
        $content = $this->SitemapService->getRobotData();
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

        $this->SitemapService->updateRobotData($request);

        return redirect()->back()->with('status', __('Content updated successfully'));
    }

    /**
     * show form favicon
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function faviconForm()
    {
        $images = $this->SitemapService->getImagesData();

        return view('Content::admin.sitemap.favicon', compact('images'), [
            'routes' => resolveInertiaRoutes(config('menus.pages'))
        ]);
    }

    /**
     * Update favicon
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateFavicon(Request $request)
    {
        $request->validate([
            'images' => 'nullable|array|max:20',
            'images.*' => 'file|mimes:jpg,jpeg,png,gif,webp,ico,svg,bmp,avif|max:5120',
        ]);

        $this->SitemapService->updateFavicon($request);

        return redirect()->back()->with('status', __('Public images and favicon updated successfully'));
    }

    public function deleteFavicon(string $path)
    {
        $this->SitemapService->deleteFile($path);

        return redirect()->back()->with('status', __('File deleted successfully'));
    }
}
