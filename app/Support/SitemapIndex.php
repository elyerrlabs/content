<?php

namespace Content\App\Support;

final class SitemapIndex extends \Content\Vendor\Spatie\Sitemap\SitemapIndex
{

    /**
     * Render
     * @return string
     */
    public function render(): string
    {
        $tags = $this->tags;
        $stylesheetUrl = $this->stylesheetUrl;

        return view('Content::sitemap.sitemapIndex.index')
            ->with(compact('tags', 'stylesheetUrl'))
            ->render();
    }
}
