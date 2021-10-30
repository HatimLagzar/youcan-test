<?php

namespace App\Console\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Support\Arr;

class CategoryConsoleService
{
    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param array $categoriesNames
     * @return array
     */
    public function getIdsFromNames(array $categoriesNames): array
    {
        $categoriesIds = array_map(function ($name) {
            $category = $this->categoryRepository->findByName($name);
            if ($category) {
                return $category->id;
            }

            return null;
        }, $categoriesNames);

        return Arr::where($categoriesIds, function ($id) {
            return !empty($id);
        });
    }
}
