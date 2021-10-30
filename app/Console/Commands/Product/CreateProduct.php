<?php

namespace App\Console\Commands\Product;

use App\Console\Services\CategoryConsoleService;
use App\Console\Services\InputService;
use App\Console\Services\UploadService;
use App\Exceptions\DatabaseManipulationException;
use App\Exceptions\UploadExternalFileException;
use App\Exceptions\ValidationException;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Console\Command;

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

    protected UploadService $uploadService;

    protected CategoryConsoleService $categoryConsoleService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        ProductService         $productService,
        CategoryService        $categoryService,
        InputService           $inputService,
        UploadService          $uploadService,
        CategoryConsoleService $categoryConsoleService
    )
    {
        parent::__construct();
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->inputService = $inputService;
        $this->uploadService = $uploadService;
        $this->categoryConsoleService = $categoryConsoleService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $name = $this->inputService->ask($this, 'Enter product name');
        $description = $this->inputService->ask($this, 'Enter product description');
        $price = $this->inputService->askForNumber($this, 'Enter product price (Number)');
        $imageSrc = $this->inputService->ask($this, 'Enter product image, URL or local path');

        $productCategoriesChoices = $this->categoryService->getAllNamesAsArray();
        if (count($productCategoriesChoices) === 0) {
            $this->info('0 categories found.');
        }

        $choices = $this->inputService->askForMultipleChoices($this, 'Select product categories, to use multiple categories use comma (Cat One, Cat Five)...', ['None', ...$productCategoriesChoices]);
        $choices = $this->categoryConsoleService->getIdsFromNames($choices);

        try {
            $imageFile = $this->uploadService->uploadExternalResource($imageSrc);
            $this->productService->create([
                'name' => $name,
                'description' => $description,
                'price' => floatval($price),
                'image' => $imageFile,
                'categories' => $choices ?? null
            ]);

            $this->info('Product created successfully.');
            return Command::SUCCESS;
        } catch (ValidationException | DatabaseManipulationException $exception) {
            $this->error($exception->getMessage());
            return Command::FAILURE;
        } catch (UploadExternalFileException $exception) {
            if (isset($imageFile) && !empty($imageFile)) {
                $this->productService->deleteTemporaryFile($imageFile);
            }

            $this->error($exception->getMessage());
            return Command::FAILURE;
        }
    }
}
