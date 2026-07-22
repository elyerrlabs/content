<?php
namespace Content\App\Support;

final class Sitemap extends \Content\Vendor\Spatie\Sitemap\Sitemap
{
    /**
     * Render
     * @return string
     */
    public function render(): string
    {
        $tags = collect($this->tags)->unique('url')->filter();
        $stylesheetUrl = $this->stylesheetUrl;
        return view('Content::sitemap.sitemap')->with(compact('tags', 'stylesheetUrl'))->render();
    }
}
