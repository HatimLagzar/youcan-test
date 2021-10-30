<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

class ProductCategoryRepository
{
    public function getAll(): Collection
    {
        return DB::table('products_categories')->get();
    }

    public function findById(int $id): ?stdClass
    {
        return DB::table('products_categories')
            ->where('id', '=', $id)
            ->get()
            ->first();
    }

    public function store(int $categoryId, int $productId): ?stdClass
    {
        $id = DB::table('products_categories')
            ->insertGetId([
                'category_id' => $categoryId,
                'product_id' => $productId,
            ]);

        return $this->findById($id);
    }
}
