<?php
namespace Content\App\Repositories;

use Content\App\Models\File;

class FileRepository
{
    public function __construct(protected File $file)
    {
    }

    /**
     * Query builder
     * @return \Illuminate\Database\Eloquent\Builder<File>
     */
    public function query()
    {
        return $this->file->query();
    }


    /**
     * Find
     * @param string $id
     * @return File|\stdClass|null
     */
    public function find(string $id)
    {
        return $this->query()->where('id', $id)->first();
    }

    /**
     * Create new file
     * @param array $data
     * @return File
     */
    public function create(array $data)
    {
        return $this->file->create($data);
    }

    /**
     * Update file
     * @param File $file
     * @param array $data
     * @return File
     */
    public function update(File $file, array $data)
    {
        $file->fill($data);
        $file->save();
        return $file;
    }

    /**
     * Delete the given file record.
     *
     * @param File $file
     * @return bool|null
     */
    public function delete(File $file)
    {
        return $file->delete();
    }
}
