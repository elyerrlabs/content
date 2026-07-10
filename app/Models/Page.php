<?php

namespace Content\App\Models;
use Content\App\Models\Master;





class Page extends Master
{
    public $table = 'content_pages';

    protected $fillable = [
        'name',
        'slug',
        'path',
        'is_published',
        'is_draft',
        'index'
    ];
}
