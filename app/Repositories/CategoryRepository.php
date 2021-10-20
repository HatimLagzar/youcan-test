<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    protected Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getAll(array $columns = [])
    {
        return $this->category->all(...$columns);
    }

    public function findById(int $id)
    {
        return $this->category->find($id);
    }

    public function findByName(string $name)
    {
        return $this->category->where('name', '=', $name)->first();
    }

    public function create(array $inputs)
    {
        return $this->category->create($inputs);
    }
}
