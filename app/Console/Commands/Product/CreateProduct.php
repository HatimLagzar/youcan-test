<?php

namespace App\Console\Commands\Product;

use App\Console\Services\InputService;
use App\Exceptions\DatabaseManipulationException;
use App\Exceptions\ImageValidationException;
use App\Exceptions\ValidationException;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class CreateProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a product';

    protected ProductService $productService;

    protected CategoryService $categoryService;

    protected InputService $inputService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        ProductService  $productService,
        CategoryService $categoryService,
        InputService    $inputService
    )
    {
        parent::__construct();
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->inputService = $inputService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $name = $this->inputService->ask('Enter product name');
        $description = $this->inputService->ask('Enter product description');
        $price = $this->inputService->askForNumber('Enter product price (Number)');
        $imageSrc = $this->inputService->ask('Enter product image, URL or local path');

        $productCategoriesChoices = $this->categoryService
            ->getAll(['name'])
            ->toArray();

        if (count($productCategoriesChoices) === 0) {
            $this->info('0 categories found.');
        }

        $productCategoriesChoices = ['None', ...Arr::flatten($productCategoriesChoices)];
        $choices = $this->choice(
            'Select product categories, to use multiple categories use comma (Cat One, Cat Five)...',
            $productCategoriesChoices,
            0,
            null,
            true
        );

        $choices = array_map(function ($choice) {
            $category = $this->categoryService->findByName($choice);
            if ($category) {
                return $category->id;
            }
        }, $choices);

        $choices = Arr::where($choices, function ($choice) {
            if ($choice) {
                return $choice;
            }
        });

        try {
            $this->productService->create([
                'name' => $name,
                'description' => $description,
                'price' => floatval($price),
                'image' => $imageSrc,
                'categories' => $choices ?? null
            ]);

            $this->info('Product created successfully.');
            return Command::SUCCESS;
        } catch (ValidationException | ImageValidationException | DatabaseManipulationException $exception) {
            $this->error($exception->getMessage());
            return Command::FAILURE;
        }
    }
}
