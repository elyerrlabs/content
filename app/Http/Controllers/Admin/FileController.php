<?php

namespace Content\App\Http\Controllers\Admin;

use App\Http\Controllers\WebController;
use Content\App\Models\File;
use Content\App\Services\FileService;
use Illuminate\Http\Request;

class FileController extends WebController
{
    public function __construct(protected FileService $fileService)
    {
        parent::__construct();
        $this->middleware("userCanAny:developer:content-seo:full,developer:content-seo:view")->only('index', 'show');
        $this->middleware("userCanAny:developer:content-seo:full,developer:content-seo:create")->only('store', );
        $this->middleware("userCanAny:developer:content-seo:full,developer:content-seo:destroy")->only('delete');
    }

    /**
     * Show the gallery index with paginated files.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $routes = resolveInertiaRoutes(config('menus.pages'));
        $files = $this->fileService->search($request)->paginate($request->input('per_page', 100));

        return view('Content::admin.file.file', compact('routes', 'files'));
    }

    /**
     * Store up to five gallery images per request.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'files' => ['required', 'array', 'min:1', 'max:5'],
            'files.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ]);

        $this->fileService->createMany($request->file('files', []));

        return back()->with('success', __('Files created successfully'));
    }

    /**
     * Delete a file from the database and storage.
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $this->fileService->delete($id);

        return redirect()->route('module.content.admin.files.index')->with('success', __('File delete succesfuly'));
    }

    /**
     * Format a gallery file for JSON responses.
     *
     * @param File $file
     * @return array<string, mixed>
     */
    protected function presentFile(File $file): array
    {
        return [
            'id' => $file->id,
            'disk' => $file->disk,
            'path' => $file->path,
            'name' => $file->name,
            'original_name' => $file->original_name,
            'mime_type' => $file->mime_type,
            'size' => $file->size,
            'created_at' => optional($file->created_at)->toDateTimeString(),
            'updated_at' => optional($file->updated_at)->toDateTimeString(),
            'url' => $file->url,
            'links' => $file->links,
        ];
    }
}
