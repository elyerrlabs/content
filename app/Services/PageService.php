<?php

namespace Content\App\Services;

use Elyerr\ApiResponse\Exceptions\ReportError;
use Content\App\Models\Page;
use Content\App\Services\SitemapService;
use Content\App\Repositories\PageRepository;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

final class PageService
{

    /**
     * Schema
     * @var string
     */
    protected $schema;

    /**
     * real path
     * @var string
     */
    protected $realPath;

    /**
     * Repository
     * @var string
     */
    protected $repository;


    /**
     * Construct
     * @param PageRepository $pageRepository
     */
    public function __construct(protected PageRepository $pageRepository, protected SitemapService $SitemapService)
    {
        $this->repository = __DIR__ . "/../../resources/views/schemas";
        $this->schema = base_path('resources/views/pages/layouts/schema.blade.php');
        $this->realPath = base_path('resources/views/pages');

        $this->createBaseDirectory();
    }

    /**
     * Create a base directory
     * @return void
     */
    private function createBaseDirectory()
    {
        $root = base_path('resources/views');
        $layouts = "$root/pages/layouts";
        $draft = "$root/pages/draft";
        $published = "$root/pages/published";

        if (!is_dir($layouts)) {
            mkdir($layouts, 0750, true);
        }

        if (!is_dir($draft)) {
            mkdir($draft, 0750, true);
        }

        if (!is_dir($published)) {
            mkdir($published, 0750, true);
        }
    }


    public function getRealPath()
    {
        return $this->realPath;
    }

    /**
     * Draft path generate
     * @param mixed $slug
     * @return string
     */
    public function draftPathGenerate($slug)
    {
        return rtrim($this->realPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "draft" . DIRECTORY_SEPARATOR . $slug . '.blade.php';
    }

    /**
     * Published path generator
     * @param mixed $slug
     * @return string
     */
    public function publishedPathGenerate($slug)
    {
        return rtrim($this->realPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "published" . DIRECTORY_SEPARATOR . $slug . '.blade.php';
    }

    /**
     * search
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Page>
     */
    public function search(Request $request)
    {
        $query = $this->pageRepository->query();

        $query->when($request->filled('name'), fn($q) => $q->whereRaw('LOWER(slug) LIKE ?', ["%" . $request->name . "%"]));

        $query->when($request->filled('index'), fn($q) => $q->where('index', $request->input('index')));

        $query->orderBy($request->filled('order_by') ? $request->order_by : 'created_at', $request->filled('order_type') ? $request->order_type : 'asc');

        return $query;
    }

    /**
     * Find
     * @param string $slug
     * @return object|Page|\stdClass
     */
    public function findPage(string $slug)
    {
        $page = $this->pageRepository->query()
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return $page;
    }

    /**
     * Edit
     * @param string $id
     * @throws ReportError
     * @return object|Page|\stdClass|null
     */
    public function edit(string $id)
    {
        $page = $this->pageRepository->find($id);

        if (empty($page)) {
            throw new ReportError(__('Page not found'), 404);
        }

        if ($page->is_draft) {

            $drafPath = $this->draftPathGenerate($page->slug);

            if (!File::exists($drafPath)) {
                File::copy($page->path, $drafPath);
            }

            $page->content = file_get_contents($drafPath);

            $page->path = $drafPath;

        } else {
            $publishedPath = $this->publishedPathGenerate($page->slug);

            $page->content = file_get_contents($publishedPath);
        }

        return $page;
    }

    /**
     * Find page
     * @param string $id
     * @return object|Page|\stdClass|null
     */
    public function find(string $id)
    {
        return $this->pageRepository->query()->where('id', $id)->first();
    }

    /**
     * Create page
     * @param array $data
     * @throws \Exception
     * @return Page
     */
    public function create(array $data)
    {
        $slug = Str::slug($data['slug']);

        if (!File::exists($this->realPath)) {
            File::makeDirectory($this->realPath, 0755, true);
        }

        $path = rtrim($this->realPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "published" . DIRECTORY_SEPARATOR . $slug . '.blade.php';

        if (!File::exists($this->schema)) {
            throw new \Exception('Schema template not found');
        }

        if (!File::exists($path)) {
            File::copy($this->schema, $path);
        }

        return $this->pageRepository->create([
            'name' => $data['name'],
            'slug' => $slug,
            'path' => $path,
            'is_draft' => $data['is_draft'] ?? true,
            'is_published' => $data['is_draft'] ?? false,
            'index' => $data['index'] ?? false
        ]);
    }

    /**
     * Update page
     * @param string $id
     * @param array $data
     * @throws ReportError
     * @throws \Exception
     * @return object|Page|\stdClass|null
     */
    public function update(string $id, array $data)
    {
        $model = $this->pageRepository->find($id);

        if (empty($model)) {
            throw new ReportError(__("Error Processing Request"), 400);
        }

        $newSlug = Str::slug($data['slug'] ?? $model->slug);
        $currentDraftPath = $this->draftPathGenerate($model->slug);
        $newDraftPath = $this->draftPathGenerate($newSlug);
        $publishedPath = $this->publishedPathGenerate($newSlug);
        $content = $data['content'] ?? null;
        $isDraft = filter_var($data['is_draft'] ?? true, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $isDraft = $isDraft ?? false;

        if (!File::exists($currentDraftPath)) {
            if (File::exists($this->schema)) {
                File::copy($this->schema, $currentDraftPath);
            }
        }

        if ($currentDraftPath !== $newDraftPath && File::exists($currentDraftPath)) {
            File::move($currentDraftPath, $newDraftPath);
        }

        if ($content !== null) {
            $this->updateFile($newDraftPath, $content);
        }

        if (!$isDraft) {
            if (!File::exists($newDraftPath)) {
                throw new ReportError(__("Draft page cannot be found"), 404);
            }

            File::copy($newDraftPath, $publishedPath);
        }

        $this->pageRepository->update($model, [
            'name' => $data['name'] ?? $model->name,
            'slug' => $newSlug,
            'path' => $publishedPath,
            'is_published' => $data['is_published'] ?? false,
            'is_draft' => $isDraft,
            'index' => $data['index'] ?? false
        ]);

        return $model;
    }

    /**
     * Delete
     * @param string $id
     * @throws ReportError
     * @return bool
     */
    public function delete(string $id)
    {
        $model = $this->pageRepository->find($id);

        if (empty($model)) {
            throw new ReportError(__("Page not found"), 400);
        }

        if (!empty($model->path) && File::exists($model->path)) {
            File::delete($model->path);
        }

        if (!empty($draftPath = $this->draftPathGenerate($model->slug)) && File::exists($draftPath)) {
            File::delete($draftPath);
        }


        $model->delete();

        return true;
    }

    /**
     * Reset file
     * @param string $id
     * @throws ReportError
     * @return void
     */
    public function reset(string $id)
    {
        $model = $this->pageRepository->find($id);

        if (empty($model)) {
            throw new ReportError(__("Page not found"), 404);
        }

        $draft = $this->draftPathGenerate($model->slug);
        $prod_path = $model->path;

        if (!file_exists($prod_path)) {
            throw new ReportError(__("We can't find the production file to reset"), 404);
        }

        File::copy($prod_path, $draft);
    }

    /**
     * Update file
     * @param string $path
     * @param   $content
     * @return void
     */
    public function updateFile(string $path, $content)
    {
        if (file_exists($path)) {
            File::put($path, $content);
        }
    }

    /**
     * Load layouts
     * @param string $name
     * @return bool|string
     */
    public function loadLayout(string $name)
    {
        return file_get_contents($this->loadLayoutPath($name));
    }

    public function loadLayoutPath(string $name)
    {

        $schema = __DIR__ . "/../../resources/views/schemas/$name.blade.php";

        $path = rtrim($this->realPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . $name . '.blade.php';

        if (!file_exists($path)) {
            copy($schema, $path);
        }

        return $path;
    }

    /**
     * Index pages
     * @return void
     */
    public function indexPages()
    {
        $this->pageRepository->query()->where('index', true)->chunk(1000, function ($chunk, $index) {

            $filename = "posts_{$index}.xml";
            // public path
            $path = public_path("sitemaps/{$filename}");
            // sitemap url
            $url = ltrim(config('app.url'), '/') . "/sitemaps/$filename";

            // Remove file and url
            if (File::exists($path)) {
                $this->SitemapService->remove($url);
                File::delete($path);
            }

            foreach ($chunk as $page) {
                $this->SitemapService->register(
                    "posts_{$index}",
                    route('pages', $page->slug),
                    null,
                    'weekly',
                    0.5
                );
            }
        });
    }

    public function renderDraftError($e, $page)
    {
        return response()->view('Content::errors.builder', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => collect($e->getTrace())
                ->take(10)
                ->toArray(),
            'page' => $page
        ], 500);
    }

    /**
     * Copy files
     * @return void
     */
    public function copyFiles()
    {
        $scanDir = scandir($this->repository);


        foreach ($scanDir as $key => $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }

            $repo = $this->repository . "/$file";
            $target = $this->realPath . "/layouts/$file";

            if (!file_exists($target)) {
                copy($repo, $target);
            }
        }
    }
}
