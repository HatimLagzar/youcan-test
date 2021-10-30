<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

class CategoryRepository
{
    /**
     * Retrieve all the categories
     *
     * @param array $columns price what columns you want to retrive, empty equivalent to *
     * @return Collection
     */
    public function getAll(array $columns = []): Collection
    {
        return DB::table('categories')
            ->select(...$columns)
            ->get();
    }

    /**
     * Search for a category by id and return the found category
     *
     * @param int $id the id of the category we're looking for
     * @return stdClass|null
     */
    public function findById(int $id): ?stdClass
    {
        return DB::table('categories')
            ->where('id', '=', $id)
            ->get()
            ->first();
    }

    /**
     * Search for a category by name and return the found category
     *
     * @param string $name the name of the category we're looking for
     * @return stdClass|null
     */
    public function findByName(string $name): ?stdClass
    {
        return DB::table('categories')
            ->where('name', '=', $name)
            ->get()
            ->first();
    }

    /**
     * Create a category
     *
     * @param array $inputs the values of the category we want to create
     * @return stdClass|null
     */
    public function create(array $inputs): ?stdClass
    {
        $id = DB::table('categories')->insertGetId($inputs);
        return $this->findById($id);
    }
}
