<?php

namespace Content\App\Models;

use App\Contracts\Translatable;
use App\Support\HasTranslation;
use Content\App\Models\Master;

class Page extends Master implements Translatable
{
    use HasTranslation;
    
    public $table = 'content_pages';

    protected $fillable = [
        'name',
        'slug',
        'path',
        'is_published',
        'is_draft',
        'index'
    ];

    /**
     * Apply Translation for path
     * @return string[]
     */
    public function getTranslatableAttributes(): array
    {
        return [
            'path',
            'name',
            'slug'
        ];
    }
}
