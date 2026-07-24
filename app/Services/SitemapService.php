<?php

namespace Content\App\Services;

use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use RuntimeException;
use Content\App\Support\SitemapIndex;
use Content\App\Support\Sitemap;
use Carbon\Carbon;
use Content\Vendor\Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\Storage;

class SitemapService
{
    /**
     * Sitemap path
     * @var string
     */
    private $sitemapPath;

    /**
     * Sitemap name
     * @var string
     */
    private $sitemapIndexPath;

    /**
     * Custom pages
     * @var string
     */
    private $customSitemap;

    /**
     * Construct
     * 
     */
    public function __construct()
    {
        $this->sitemapPath = public_path('sitemaps');
        $this->sitemapIndexPath = $this->sitemapPath . "/index.xml";
        $this->customSitemap = "custom.xml";
    }

    /**
     * Get the sitemap file name
     * @param string $fileName
     * @return string
     */
    public function getSitemapPath(string $fileName)
    {
        return $this->sitemapPath . "/$fileName.xml";
    }

    /**
     * Register sitemaps
     * @param string $fileName
     * @param array $alternates
     * @throws RuntimeException
     * @return void
     */
    public function register(string $fileName, array $alternates): void
    {
        /*
         * Create sitemap directory if it does not exist
         */
        if (!is_dir($this->sitemapPath)) {
            mkdir($this->sitemapPath, 0755, true);
        }

        /*
         * Validate sitemap data
         */
        if (empty($alternates['en']['url'])) {
            throw new RuntimeException(__('The english route is required.'));
        }

        $sitemapFile = $this->getSitemapPath($fileName);

        $this->manageSitemaIndex($fileName);

        /*
         * Create sitemap instance
         */
        $sitemap = Sitemap::create();

        /*
         * Load existing sitemap URLs
         *
         * NOTE:
         * Existing XML entries are loaded without SEO metadata.
         * For a full regeneration process, it is recommended
         * to rebuild the sitemap from the source data.
         */
        if (file_exists($sitemapFile)) {

            $xml = simplexml_load_file($sitemapFile);

            $namespaces = $xml->getNamespaces(true);

            $urlset = $xml->children($namespaces['']);

            foreach ($urlset->url as $item) {

                $url = Url::create((string) $item->loc)
                    ->setLastModificationDate(
                        Carbon::parse((string) $item->lastmod)
                    );

                /*
                 * Load hreflang alternates
                 */
                $xhtml = $item->children($namespaces['xhtml']);

                foreach ($xhtml->link as $link) {
                    $attributes = $link->attributes();

                    $url->addAlternate(
                        (string) $attributes->href,
                        (string) $attributes->hreflang
                    );
                }

                $sitemap->add($url);
            }
        }


        /*
         * Create canonical URL using the default language (en)
         *
         * The English version is the base URL and does not
         * include the language prefix.
         */
        $url = Url::create($alternates['en']['url'])->setLastModificationDate(now());

        /*
         * Add alternate language URLs using hreflang
         *
         * Each translated version points to the same content
         * in a different language.
         */
        foreach ($alternates as $locale => $attributes) {

            if (empty($attributes['url'])) {
                continue;
            }

            $url->addAlternate($attributes['url'], $locale);
        }


        /*
         * Add images associated with the canonical URL
         *
         * This allows future support for localized images,
         * captions, titles and other SEO image metadata.
         */
        foreach ($alternates['en']['images'] ?? [] as $image) {

            $url->addImage(
                $image['url'],
                $image['caption'] ?? null,
                $image['title'] ?? null
            );
        }


        /*
         * Add generated URL to sitemap
         */
        $sitemap->add($url);


        /*
         * Write sitemap XML file
         */
        $sitemap->writeToFile($sitemapFile);
    }

    /**
     * Manage sitemap index
     * @param string $sitemapChild
     * @return void
     */
    public function manageSitemaIndex(string $sitemapChild)
    {
        $index = SitemapIndex::create();

        $file = "/sitemaps/{$sitemapChild}.xml";

        if (file_exists($this->sitemapIndexPath)) {

            $xml = simplexml_load_file($this->sitemapIndexPath);

            $namespaces = $xml->getNamespaces(true);

            $root = $xml->children($namespaces['']);

            $exists = false;

            foreach ($root->sitemap as $item) {

                $loc = (string) $item->loc;

                // omit unexisting files
                if (!file_exists(public_path(str_replace(url(''), '', url($loc))))) {
                    continue;
                }

                /*
                 * Preserve existing sitemap
                 */
                $index->add($loc);

                /*
                 * Avoid duplicate registrations
                 */
                if ($loc === url($file)) {
                    $exists = true;
                }
            }

            /*
             * Register new sitemap if it does not exist
             */
            if (!$exists) {
                $index->add($file);
            }

        } else {

            /*
             * Create the first sitemap entry
             */
            $index->add($file);
        }

        /*
         * Save sitemap index
         */
        $index->writeToFile($this->sitemapIndexPath);
    }

    /**
     * Delete sitemaps by prefix
     * @param string $prefix
     * @return void
     */
    public function deleteByPrefix(string $prefix)
    {
        $files = array_diff(scandir($this->sitemapPath), ['.', '..']);

        foreach ($files as $key => $value) {
            if (str_starts_with($value, $prefix)) { // filter by prefix
                $path = $this->sitemapPath . "/$value";
                if (file_exists($path)) { // check verification path
                    @unlink($path);
                }
            }
        }

        $this->refreshSitemapIndex();
    }

    /**
     * Refresh sitemapIndex
     * @return void
     */
    public function refreshSitemapIndex()
    {
        $index = SitemapIndex::create();

        if (file_exists($this->sitemapIndexPath)) {

            $xml = simplexml_load_file($this->sitemapIndexPath);

            $namespaces = $xml->getNamespaces(true);

            $root = $xml->children($namespaces['']);

            foreach ($root->sitemap as $item) {

                $loc = (string) $item->loc;

                // omit unexisting files
                if (!file_exists(public_path(str_replace(url(''), '', url($loc))))) {
                    continue;
                }

                /*
                 * Preserve existing sitemap
                 */
                $index->add($loc);
            }
        }

        /*
         * Save sitemap index
         */
        $index->writeToFile($this->sitemapIndexPath);
    }

    /**
     * Reset robots and sitemaps
     * @return void
     */
    public function reset()
    {
        // Delete all sitemap files inside the directory
        $files = array_diff(scandir($this->sitemapPath), ['.', '..', $this->customSitemap]);

        if (is_dir($this->sitemapPath)) {
            foreach ($files as $file) {
                @unlink(public_path("sitemaps/" . $file));
            }
        }

        // Reset robots.txt to block indexing
        @unlink(public_path('robots.txt'));
        Storage::disk('content_backups')->delete('robots.txt');

        $this->getOrUpdateContent(
            "robots.txt",
            "User-agent: *\nDisallow: /",
            true
        );
    }

    /**
     * Get or update public file content
     * @param string $relativePath
     * @param string $defaultContent
     * @param bool $update
     * @return bool|string
     */
    public function getOrUpdateContent(string $relativePath, string $defaultContent = '', bool $update = false, bool $saveBackup = false)
    {
        $path = public_path($relativePath);

        if (!file_exists($path) || $update) {
            file_put_contents($relativePath, $defaultContent);
        }

        if ($saveBackup) {
            Storage::disk('content_backups')->put($relativePath, file_get_contents($path));
        }

        return file_get_contents($path);
    }

    /**
     * Ger or update custom sitemap content
     * @param string $defaultContent
     * @param bool $update
     * @return bool|string
     */
    public function getOrUpdateCustomSitemap(string $defaultContent = '', bool $update = false)
    {
        // Set real sitemap path 
        $relativePath = "sitemaps/" . $this->customSitemap;

        $content = $defaultContent;

        if (!file_exists(public_path($relativePath))) {

            $content = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
            xmlns:xhtml="http://www.w3.org/1999/xhtml"
            xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
            xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"
            xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
            <!--Add custom pages-->       
            </urlset>
            XML;
        }


        // Add custom sitemap page to the index map
        $this->manageSitemaIndex(str_replace('.xml', '', $this->customSitemap));

        return $this->getOrUpdateContent($relativePath, $content, $update);
    }


    public function backupFiles()
    {
        $files = [];

        $files['robots.txt'] = Storage::disk('content_backups')->path('robots.txt');

        foreach ($files as $key => $value) {
            if (file_exists($value)) {
                copy($value, public_path($key));
            }
        }

    }
}
