<?php

namespace Content\App\Services;

use App\Contracts\Translatable;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Content\App\Models\Page;
use Content\App\Services\SitemapService;
use Content\App\Repositories\PageRepository;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

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
     * Render page
     * @param string $lang
     * @param string $slug
     * @throws ReportError
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function renderPages(string $lang = 'en', string $slug = '')
    {
        $user = request()->user();

        // Fixed slug
        $slug = empty($slug) && !in_array($lang, array_keys(config('app.langs'))) ? $lang : $slug;
        // Fixed lang support
        $lang = in_array($lang, array_keys(config('app.langs'))) ? $lang : 'en';

        $query = $this->pageRepository->query();

        $query->where('is_published', true);

        if ($lang === 'en') {
            $query->where('slug', strtolower($slug));
        } else {
            $query->whereHas('translations', function ($query) use ($lang, $slug) {
                $query->where('attribute', 'slug')
                    ->where('locale', $lang)
                    ->whereRaw('LOWER(value) = ?', [strtolower($slug)]);
            });
        }

        $page = $query->first();

        if (!$page) {
            throw new ReportError(__('Page not found'), 404);
        }

        $path = $lang === 'en'
            ? $page->path
            : $page->{"path_{$lang}"};

        if (!file_exists($path)) {
            throw new ReportError(__('Page not found'), 404);
        }

        $currentLang = !empty($user) ? $user->lang : app()->getLocale();

        // Redirect current lang
        if ($currentLang != $lang) {
            $page = $page->localize();
            return redirect()->route('pages', ['locale' => $currentLang, 'slug' => $page->slug]);
        }

        return view()->file($path);
    }

    /**
     * Show content to show
     * @param string $id
     * @throws ReportError
     * @return Page
     */
    public function edit(string $id)
    {
        $page = $this->pageRepository->find($id);

        if (empty($page)) {
            throw new ReportError(__('Page not found'), 404);
        }

        // Extract original name
        $fname = str_replace('.blade.php', '', basename($page->path));

        // Fixed file name to support langs
        $fileName = request()->input('lang', 'en') == 'en' ? $fname : $fname . "_" . request()->lang;

        // Generate draft path
        $drafPath = $this->draftPathGenerate($fileName);

        // Check the file exist, so create one
        if (!file_exists($drafPath)) {
            copy($page->path, $drafPath);
        }

        // Add file content to the object
        $page->content = file_get_contents($drafPath);

        // Replace default paht for draft path
        $page->path = $drafPath;

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
        // Create slug
        $data['slug'] = Str::slug($data['slug']);

        //Check directory
        if (!file_exists($this->realPath)) {
            File::makeDirectory($this->realPath, 0755, true);
        }

        // Create path
        $data['path'] = $this->publishedPathGenerate($data['slug']);

        // Checking schema
        if (!file_exists($this->schema)) {
            throw new \Exception('Schema template not found');
        }

        // Customize calculated field (path) , extract translatable fields first
        $fields = extractTranslationsFields(new Page(), $data, true);

        // Create path for translatable fields
        if (isset($fields['langs'])) {
            foreach ($fields['langs'] as $lang) {
                $data["path_$lang"] = $this->publishedPathGenerate($data['slug'] . "_" . $lang);

                // Add key for draft file path for only file draft creation
                $data["draft_path_$lang"] = $this->draftPathGenerate($data['slug'] . "_" . $lang);
            }
            // Add default path to the draft paths files
            $data["draft_path_en"] = $this->draftPathGenerate($data['slug']);
        }

        $page = DB::transaction(function () use ($data) {

            // Create object
            $page = $this->pageRepository->create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'path' => $data['path'],
                'is_draft' => true,
                'is_published' => false,
                'index' => false
            ]);

            // Create files for published directory
            foreach ($data as $key => $value) {
                if (str_starts_with($key, 'path')) {
                    if (!file_exists($value)) {
                        copy($this->schema, $value);
                    }
                }
            }

            // Create files for draft directory
            foreach ($data as $key => $value) {
                if (str_starts_with($key, 'draft_path')) {
                    if (!file_exists($value)) {
                        copy($this->schema, $value);
                    }
                }
            }

            // Sync all translations fields
            syncTranslations($page, $data);

            return $page;
        });


        return $page;
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
        // Fixed slugs for translatable attributos
        foreach ($data as $key => $value) {
            if (str_starts_with($key, "slug_")) {
                $data[$key] = Str::slug($value);
            }
        }

        // Customize calculated field (path) , extract translatable fields first
        $fields = extractTranslationsFields(new Page(), $data, true);

        // Create path for translatable fields
        $paths = [];
        if (isset($fields['langs'])) {
            foreach ($fields['langs'] as $lang) {
                $paths["path_$lang"] = $this->publishedPathGenerate($data['slug'] . "_" . $lang);
            }
        }

        // Create translate fields if it is does not exist
        $modelData = $model->toArray();
        foreach ($paths as $key => $path) {
            if (!isset($modelData[$key])) {
                dd(33);
                $data[$key] = $path;
            }
        }

        // Fixed default slug
        $slug = isset($data['slug']) ? Str::slug($data['slug']) : $model->slug;

        // Extract default file name
        $fname = str_replace('.blade.php', '', basename($model->path));
        // File name
        $fileName = $data['lang'] == 'en' ? $fname : $fname . "_" . $data['lang'];

        // path destinations
        $draftPath = $this->draftPathGenerate($fileName);

        // Create current draft path if it does not exist
        if (!file_exists($draftPath)) {
            // Schema verification exists
            if (file_exists($this->schema)) {
                // Create new file
                copy($this->schema, $draftPath);
            }
        }

        // Seave content
        $this->updateFile($draftPath, $data['content']);

        // Is published? 
        $isDraft = isset($data['is_draft']) ? $data['is_draft'] : true;

        // save content
        $model = $this->pageRepository->update($model, [
            'name' => $data['name'],
            'slug' => $slug,
            'is_published' => $data['is_published'] ?? false,
            'is_draft' => $isDraft,
            'index' => $data['index'] ?? false
        ]);

        // Copy draft files to published directory
        if (!$isDraft) {
            if (!file_exists($draftPath)) {
                throw new ReportError(__("Draft page cannot be found"), 404);
            }

            foreach ($model->toArray() as $key => $path) {
                if (str_starts_with($key, "path")) {
                    // Fixed draft path
                    $draftPath = str_replace('published', 'draft', $path);
                    copy($draftPath, $path);
                }
            }
        }

        // verificar path si no existen crearlos

        // Sync translations
        syncTranslations($model, $data);

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

        // Remove files
        foreach ($model->toArray() as $key => $value) {
            if (str_starts_with($key, 'path')) {

                // Remove published path
                if (file_exists($value)) {
                    unlink($value);
                }

                // create path for draft pages
                $draftPath = str_replace('published', 'draft', $value);
                // Remove draft path
                if (file_exists($draftPath)) {
                    unlink($draftPath);
                }
            }
        }

        // remove translations
        $model->translations()->delete();

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

        copy($prod_path, $draft);
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
            if (file_exists($path)) {
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
