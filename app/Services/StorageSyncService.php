<?php

namespace Content\App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

final class StorageSyncService
{
    /**
     * Local resource path.
     */
    private string $path;

    /**
     * Backup storage.
     */
    private $storage;

    public function __construct(string $path)
    {
        $this->path = $path;

        $this->storage = Storage::disk(config('filesystems.backups', 'content_local'));
    }

    /**
     * Upload the resource and its metadata.
     */
    public function backup(): void
    {
        if (!file_exists($this->localPath())) {
            throw new RuntimeException("Resource [{$this->path}] does not exist.");
        }

        $meta = [
            'revision' => (string) Str::ulid(),
            'updated_at' => now()->utc()->toIso8601String(),
        ];


        if (is_dir($this->localPath())) {


            $this->storage->deleteDirectory($this->path);
            $this->storage->makeDirectory($this->path);


            foreach (File::allFiles($this->localPath()) as $file) {

                $relative = ltrim(
                    str_replace($this->localPath(), '', $file->getPathname()),
                    DIRECTORY_SEPARATOR
                );

                $this->storage->put(
                    $this->path . '/' . $relative,
                    file_get_contents($file->getPathname())
                );
            }
        } else {
            $this->storage->put(
                $this->path,
                file_get_contents($this->localPath())
            );
        }

        $this->storage->put(
            $this->metaPath(),
            json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        file_put_contents(
            $this->localMetaPath(),
            json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Synchronize the resource from storage.
     */
    public function restore(): void
    {
        if (!$this->storage->exists($this->metaPath())) {
            return;
        }

        $remote = json_decode(
            $this->storage->get($this->metaPath()),
            true
        );

        $local = [];

        if (file_exists($this->localMetaPath())) {
            $local = json_decode(
                file_get_contents($this->localMetaPath()),
                true
            );
        }

        if (($remote['revision'] ?? null) === ($local['revision'] ?? null)) {
            return;
        }

        if ($this->storage->directoryExists($this->path)) {

            File::deleteDirectory($this->localPath());
            File::makeDirectory($this->localPath(), 0755, true);

            foreach ($this->storage->allFiles($this->path) as $file) {

                $relative = str_replace($this->path . '/', '', $file);

                File::ensureDirectoryExists(
                    dirname($this->localPath() . '/' . $relative)
                );

                file_put_contents(
                    $this->localPath() . '/' . $relative,
                    $this->storage->get($file)
                );
            }

        } else {

            File::ensureDirectoryExists(dirname($this->localPath()));

            file_put_contents(
                $this->localPath(),
                $this->storage->get($this->path)
            );
        }

        file_put_contents(
            $this->localMetaPath(),
            json_encode($remote, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Metadata filename in storage.
     */
    private function metaPath(): string
    {
        return $this->path . '.meta';
    }

    /**
     * Local metadata filename.
     */
    private function localMetaPath(): string
    {
        return base_path($this->metaPath());
    }

    /**
     * Local resource path.
     */
    private function localPath(): string
    {
        return base_path($this->path);
    }


    /**
     * Reset the synchronized resource.
     *
     * If $content is null, the local and remote resources are removed.
     * Otherwise, a new resource is created and synchronized.
     */
    public function reset(?string $content = null): void
    {
        // Remove local resource.
        if (is_dir($this->localPath())) {
            File::deleteDirectory($this->localPath());
        } elseif (file_exists($this->localPath())) {
            File::delete($this->localPath());
        }

        // Remove local metadata.
        File::delete($this->localMetaPath());

        // Remove remote resource.
        if ($this->storage->directoryExists($this->path)) {
            $this->storage->deleteDirectory($this->path);
        } else {
            $this->storage->delete($this->path);
        }

        // Remove remote metadata.
        $this->storage->delete($this->metaPath());

        // Nothing else to do.
        if ($content === null) {
            return;
        }

        // Ensure parent directory exists.
        File::ensureDirectoryExists(dirname($this->localPath()));

        // Create new local file.
        file_put_contents($this->localPath(), $content);

        // Synchronize it.
        $this->backup();
    }


    /**
     * Remove a specific file from the backup storage.
     *
     * This operation only affects the backup copy and its metadata.
     */
    public function removeBackup(string $file): void
    {
        $file = trim($file, '/');

        $this->storage->delete(
            $this->path . '/' . $file
        );

        $this->storage->delete(
            $this->path . '/' . $file . '.meta'
        );
    }
}