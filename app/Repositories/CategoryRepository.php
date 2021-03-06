<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryRepository
{
    /**
     * Retrieve all the categories
     *
     * @param array $columns price what columns you want to retrive, empty equivalent to *
     * @return Collection
     */
    public function getAll(array $columns = ['*']): Collection
    {
        return Category::all($columns);
    }

    /**
     * Search for a category by id and return the found category
     *
     * @param int $id the id of the category we're looking for
     * @return Category|null
     */
    public function findById(int $id): ?Category
    {
        return Category::where('id', '=', $id)
            ->get()
            ->first();
    }

    /**
     * Search for a category by name and return the found category
     *
     * @param string $name the name of the category we're looking for
     * @return Category|null
     */
    public function findByName(string $name): ?Category
    {
        return Category::where('name', '=', $name)
            ->get()
            ->first();
    }

    /**
     * Create a category
     *
     * @param array $inputs the values of the category we want to create
     * @return Category|null
     */
    public function create(array $inputs): ?Category
    {
        return Category::create($inputs);
    }

    public function delete(int $id): int
    {
        return Category::destroy($id);
    }
}
