<?php

namespace App\Console\Services;

use App\Services\CategoryService;
use Illuminate\Console\Command;

class ProductInputService
{
    protected InputService $inputService;
    protected CategoryService $categoryService;

    public function __construct(
        InputService    $inputService,
        CategoryService $categoryService
    )
    {
        $this->inputService = $inputService;
        $this->categoryService = $categoryService;
    }

    public function askForInptus(Command $command): array
    {
        $inputs = [];
        $inputs['name'] = $this->inputService->ask($command, 'Enter product name');
        $inputs['description'] = $this->inputService->ask($command, 'Enter product description');
        $inputs['price'] = $this->inputService->askForNumber($command, 'Enter product price (Number)');
        $inputs['price'] = floatval($inputs['price']);
        $inputs['imageSrc'] = $this->inputService->ask($command, 'Enter product image, URL or local path');
        $inputs['categories'] = $this->askForProductCategories($command);
        return $inputs;
    }

    /**
     * @param Command $command
     * @return array
     */
    public function askForProductCategories(Command $command): array
    {
        $productCategoriesChoices = $this->categoryService->getAllNamesAsArray();
        if (count($productCategoriesChoices) === 0) {
            $command->info('0 categories found.');
        }
        return $command->choice(
            'Select product categories, to use multiple categories use comma (Cat One, Cat Five)...',
            ['None', ...$productCategoriesChoices],
            0,
            null,
            true
        );
    }
}
