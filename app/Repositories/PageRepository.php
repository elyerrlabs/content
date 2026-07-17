<?php

namespace Content\App\Repositories;
use Content\App\Models\Page;

final class PageRepository
{
    /**
     * construct
     * @param Page $page
     */
    public function __construct(protected Page $page)
    {

    }

    /**
     * query
     * @return \Illuminate\Database\Eloquent\Builder<Page>
     */
    public function query()
    {
        return $this->page->query();
    }

    /**
     * find
     * @param string $id
     * @return object|Page|\stdClass|null
     */
    public function find(string $id)
    {
        return $this->query()->where('id', $id)->first();
    }

    /**
     * create
     * @param array $data
     * @return Page
     */
    public function create(array $data)
    {
        return $this->page->create($data);
    }

    /**
     * Update
     * @param Page $page
     * @param array $data
     * @return Page
     */
    public function update(Page $page, array $data)
    {
        $page->fill($data);
        $page->push();

        return $page;
    }
}
