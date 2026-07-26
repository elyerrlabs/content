<?php
namespace Content\App\Services;

use Content\App\Models\File;
use Content\App\Repositories\FileRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class FileService
{

    /**
     * Construct
     * @param FileRepository $fileRepository
     */
    public function __construct(protected FileRepository $fileRepository)
    {
    }


    /**
     * Search files by name and return a query builder.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder<File>
     */
    public function search(Request $request)
    {
        $query = $this->fileRepository->query();

        $query->when(
            $request->filled('name'),
            fn($q) => $q->whereRaw(
                'LOWER(name) LIKE ?',
                ['%' . strtolower($request->input('name')) . '%']
            )
        );

        $query->orderByDesc('created_at');

        return $query;
    }

    /**
     * Find a file by its identifier.
     *
     * @param string $id
     * @return File|\stdClass|null
     */
    public function find(string $id)
    {
        return $this->fileRepository->find($id);
    }

    /**
     * Create file
     * @param array $data
     * @return File
     */
    public function createFile(array $data)
    {
        $disk = in_array(($data['disk'] ?? null), ['content_local', 'content_s3'], true)
            ? $data['disk']
            : config('filesystems.default');

        if (!in_array($disk, ['content_local', 'content_s3'], true)) {
            $disk = 'content_local';
        }

        $file = $data['file'];
        $storedPath = $this->storeUploadedFile($disk, $file);

        return $this->fileRepository->create([
            'disk' => $disk,
            'path' => $storedPath,
            'name' => $data['name'],
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
    }

    /**
     * Create many files in a single request, capped at five uploads.
     *
     * @param array $files 
     * @return array<int, File>
     */
    public function createMany(array $files, ?string $disk = null): array
    {
        $results = [];

        foreach (array_slice($files, 0, 5) as $index => $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $results[] = $this->createFile([
                'file' => $file,
                'name' => $file->getClientOriginalName(),
                'disk' => $disk,
            ]);
        }

        return $results;
    }


    /**
     * Delete a file from storage and database.
     *
     * @param string $id
     * @return bool|null
     */
    public function delete(string $id)
    {
        $file = $this->fileRepository->find($id);

        if (empty($file)) {
            abort(404);
        }

        if (!empty($file->disk) && !empty($file->path)) {
            Storage::disk($file->disk)->delete($file->path);
        }

        return $this->fileRepository->delete($file);
    }

    /**
     * Move a file from one disk to another and update its database record.
     *
     * @param string $id
     * @param string $disk
     * @return File
     */
    public function moveToDisk(string $id, string $disk)
    {
        $file = $this->fileRepository->find($id);

        if (empty($file)) {
            abort(404);
        }

        if (!in_array($disk, ['content_local', 'content_s3'], true)) {
            abort(422, __('Invalid disk selected'));
        }

        if ($file->disk === $disk) {
            return $file;
        }

        $sourceDisk = Storage::disk($file->disk);
        $targetDisk = Storage::disk($disk);
        $targetDirectory = 'seo/' . now()->format('Y/m');
        $targetFilename = Str::uuid() . '.' . pathinfo($file->path, PATHINFO_EXTENSION);
        $targetPath = trim($targetDirectory . '/' . $targetFilename, '/');
        $stream = $sourceDisk->readStream($file->path);

        if ($stream === false) {
            abort(404);
        }

        try {
            $targetDisk->writeStream($targetPath, $stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        $sourceDisk->delete($file->path);

        return $this->fileRepository->update($file, [
            'disk' => $disk,
            'path' => $targetPath,
        ]);
    }

    /**
     * Resolve a storage path for a file upload.
     *
     * @param string $disk
     * @param UploadedFile $file
     * @return string
     */
    protected function storeUploadedFile(string $disk, UploadedFile $file): string
    {
        $directory = 'seo/' . now()->format('Y/m');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        return Storage::disk($disk)->putFileAs($directory, $file, $filename);
    }

    /**
     * Normalize a human readable gallery name.
     *
     * @param string $name
     * @param UploadedFile $file
     * @return string
     */
    protected function normalizeName(string $name, UploadedFile $file): string
    {
        $clean = trim(preg_replace('/\s+/', ' ', $name) ?? '');

        if ($clean === '') {
            $clean = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        }

        return Str::limit($clean, 100, '');
    }

    /**
     * Render a file response regardless of the storage driver.
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function renderFile(string $id)
    {
        $file = $this->fileRepository->find($id);

        if (empty($file)) {
            abort(404);
        }


        $disk = Storage::disk($file->disk);
        $stream = $disk->readStream($file->path);

        if ($stream === false) {
            abort(404);
        }

        $mimeType = $file->mime_type ?: $disk->mimeType($file->path) ?: 'application/octet-stream';
        $filename = addslashes($file->original_name ?? basename($file->path));

        return response()->stream(function () use ($stream) {
            while (!feof($stream)) {
                echo fread($stream, 8192);
            }

            fclose($stream);
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
