<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CategoryRepository
{
    public function getAll(array $columns = []): Collection
    {
        return DB::table('categories')
            ->select(...$columns)
            ->get();
    }

    public function findById(int $id): ?Category
    {
        return DB::table('categories')
            ->where('id', '=', $id)
            ->get()
            ->first();
    }

    public function findByName(string $name): ?Category
    {
        return DB::table('categories')
            ->where('name', '=', $name)
            ->get()
            ->first();
    }

    public function create(array $inputs): ?Category
    {
        $id = DB::table('categories')->insertGetId($inputs);
        return $this->findById($id);
    }
}
