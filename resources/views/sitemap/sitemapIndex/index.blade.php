<?php 
echo '<' . '?' . 'xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
@if(!empty($stylesheetUrl))
<?php 
echo '<' . '?' . 'xml-stylesheet type="text/xsl" href="' . e($stylesheetUrl) . '"?' . ">\n";
?>
@endif
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($tags as $tag)
    @include('Content::sitemap.sitemapIndex.' . $tag->getType())
@endforeach
</sitemapindex>
<?php 
