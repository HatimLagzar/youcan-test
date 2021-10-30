<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

class CategoryRepository
{
    public function getAll(array $columns = []): Collection
    {
        return DB::table('categories')
            ->select(...$columns)
            ->get();
    }

    public function findById(int $id): ?stdClass
    {
        return DB::table('categories')
            ->where('id', '=', $id)
            ->get()
            ->first();
    }

    public function findByName(string $name): ?stdClass
    {
        return DB::table('categories')
            ->where('name', '=', $name)
            ->get()
            ->first();
    }

    public function create(array $inputs): ?stdClass
    {
        $id = DB::table('categories')->insertGetId($inputs);
        return $this->findById($id);
    }
}
