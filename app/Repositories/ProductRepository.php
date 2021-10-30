<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

    public function findById(int $id): ?Product
    {
        return DB::table('products')
            ->where('id', '=', $id)
            ->get()
            ->first();
    }


    public function findByName(string $name): ?Product
    {
        return DB::table('products')
            ->where('name', '=', $name)
            ->get()
            ->first();
    }

    public function store(array $inputs): ?Product
    {
        $id = DB::table('products')->insertGetId($inputs);
        return $this->findById($id);
    }
}
