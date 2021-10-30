<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

class ProductRepository
{
    public function getAll(array $columns = []): Collection
    {
        return DB::table('products')
            ->select(...$columns)
            ->get();
    }

    public function getAllPaginated(): LengthAwarePaginator
    {
        return DB::table('products')->paginate(3);
    }

    /**
     * @param int $id
     * @return stdClass|null
     */
    public function findById(int $id): ?stdClass
    {
        return DB::table('products')
            ->where('id', '=', $id)
            ->get()
            ->first();
    }

    /**
     * @param string $name
     * @return stdClass|null
     */
    public function findByName(string $name): ?stdClass
    {
        return DB::table('products')
            ->where('name', '=', $name)
            ->get()
            ->first();
    }

    /**
     * @param array $inputs
     * @return stdClass|null
     */
    public function store(array $inputs): ?stdClass
    {
        $id = DB::table('products')->insertGetId($inputs);
        return $this->findById($id);
    }
}
