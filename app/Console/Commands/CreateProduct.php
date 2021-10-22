<?php

namespace App\Console\Commands;

use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        ProductService  $productService,
        CategoryService $categoryService
    )
    {
        parent::__construct();
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $name = null;
        $description = null;
        $price = null;
        $imageSrc = null;

        while (!$name) {
            $name = $this->ask('Enter product name');
        }

        while (!$description) {
            $description = $this->ask('Enter product description');
        }

        while (!$price || !is_numeric($price)) {
            $price = $this->ask('Enter product price (Number)');
        }

        while (!$imageSrc) {
            $image_src = $this->ask('Enter product image, URL or local path');
        }

        $this->table(
            ['ID', 'Name', 'Parent'],
            $this->categoryService->getAll(['id', 'name', 'parent_id'])->toArray()
        );

        $productCategoriesChoices = $this->categoryService
            ->getAll(['name'])
            ->toArray();

        if (count($productCategoriesChoices) === 0) {
            $this->info('0 categories found.');
            return Command::FAILURE;
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

        DB::beginTransaction();
        $response = $this->productService->create([
            'name' => $name,
            'description' => $price,
            'price' => floatval($price),
            'image' => $image_src,
            'categories' => $choices ?? null
        ]);

        if (!$response->original['status'] != 200) {
            DB::rollBack();
            $this->error($response->original['msg']);
            return Command::FAILURE;
        }

        DB::commit();
        $this->info('Product created successfully.');
        return Command::SUCCESS;
    }
}
