<?php

namespace Content\App\Models;

class File extends Master
{
    /**
     * Table name.
     *
     * @var string
     */
    public $table = 'content_files';

    protected $fillable = [
        'disk',
        'path',
        'name',
        'original_name',
        'mime_type',
        'size',
    ];

    /**
     * Build the public URL for the stored file.
     *
     * @return string|null
     */
    public function getUrlAttribute(): ?string
    {
        if (empty($this->disk) || empty($this->path)) {
            return null;
        }

        return route('module.content.web.render.file', ['id' => $this->id]);
    }

    /**
     * Expose public links used by the gallery UI.
     *
     * @return array<string, string|null>
     */
    public function getLinksAttribute(): array
    {
        return [
            'show' => $this->url,
            'copy' => $this->url,
        ];
    }
}
