<?php

namespace Content\App\Http\Controllers\Admin;

use App\Http\Controllers\WebController;
use Content\App\Jobs\SitemapIndexJob;
use Content\App\Services\PageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Content\App\Models\Page;
use Content\App\Rules\UniqueTranslation;

final class PageController extends WebController
{

    public function __construct(protected PageService $pageService)
    {
        parent::__construct();
        $this->middleware('userCanAny:developer:content-pages:full,developer:content-pages:view')->only('index', 'show', 'edit');
        $this->middleware('userCanAny:developer:content-pages:full,developer:content-pages:create')->only('store', 'generateSitemapFile');
        $this->middleware('userCanAny:developer:content-pages:full,developer:content-pages:update')->only('update');
        $this->middleware('userCanAny:developer:content-pages:full,developer:content-pages:destroy')->only('destroy');
    }

    /**
     * Index
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $pages = $this->pageService->search($request);
        $pages = $pages->orderBy('updated_at', 'desc')->paginate(15);

        return view('Content::admin.pages.pages', compact('pages'), [
            'routes' => resolveInertiaRoutes(config('menus.pages'))
        ]);
    }

    /**
     * Store new page
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $pageId = $page->id ?? null;

        $this->validate($request, [
            'name' => ['required', new UniqueTranslation(new Page())],
            'slug' => [
                function ($attribute, $value, $fail) use ($pageId) {
                    $query = DB::table('content_pages');

                    if ($pageId) {
                        $query->where('id', '!=', $pageId);
                    }

                    if (empty($value)) {
                        $exists = $query
                            ->where(function ($q) {
                                $q->whereNull('slug')
                                    ->orWhere('slug', '');
                            })
                            ->exists();

                        if ($exists) {
                            $fail('Only one page without a slug (landing page) is allowed.');
                        }

                        return;
                    }

                    if ($query->where('slug', $value)->exists()) {
                        $fail('The slug has already been taken.');
                    }
                }
            ]
        ]);

        $page = $this->pageService->create($request->toArray());

        return redirect()->route('module.content.admin.pages.edit', ['page' => $page->id])->with('status', __('Page creation successfully'));
    }

    /**
     * Page preview on dev mode
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $page = $this->pageService->edit($id);

        try {

            return response(view()->file($page->path)->render());

        } catch (\Throwable $e) {

            if ($page->is_draft) {
                return $this->pageService->renderDraftError($e, $page);
            }

            abort(500);
        }
    }

    /**
     * edit page
     * @param string $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(string $id)
    {
        $page = $this->pageService->edit($id);

        return view('Content::admin.pages.edit', compact('page'), [
            'routes' => resolveInertiaRoutes(config('menus.pages')),
            'edit' => route("module.content.admin.pages.edit", [
                'page' => $page->id
            ])
        ]);
    }


    /**
     * update
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => ['required', new UniqueTranslation(new Page(), $id)],
            'slug' => [
                function ($attribute, $value, $fail) use ($id) {
                    $query = DB::table('content_pages');

                    if ($id) {
                        $query->where('id', '!=', $id);
                    }

                    if (empty($value)) {
                        $exists = $query
                            ->where(function ($q) {
                                $q->whereNull('slug')
                                    ->orWhere('slug', '');
                            })
                            ->exists();

                        if ($exists) {
                            $fail('Only one page without a slug (landing page) is allowed.');
                        }

                        return;
                    }

                    if ($query->where('slug', $value)->exists()) {
                        $fail('The slug has already been taken.');
                    }
                }
            ]
        ]);

        $this->pageService->update($id, $request->toArray());

        return back()->with('status', __('Page saved successfully'));
    }

    /**
     * Delete 
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $this->pageService->delete($id);

        return redirect()->route('module.content.admin.pages.index')->with('status', __('Page deleted successfully'));
    }

    /**
     * generate sitemap index
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateSitemapFile()
    {
        SitemapIndexJob::dispatch();

        return back()->with('status', __('Sitemap index has been generating'));
    }

    /**
     * Reset
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(string $id)
    {
        $this->pageService->reset($id);

        return redirect()->route('module.content.admin.pages.edit', ['page' => $id])
            ->with('status', __('Page reseted successfully'));
    }
}
