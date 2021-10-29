<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryRepository
{
    protected Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getAll(array $columns = []): Collection
    {
        return $this->category->all(...$columns);
    }

    public function findById(int $id): ?Category
    {
        return $this->category->find($id);
    }

    public function findByName(string $name): ?Category
    {
        return $this->category->where('name', '=', $name)->first();
    }

    public function create(array $inputs): Category
    {
        return $this->category->create($inputs);
    }
}
