<?php

namespace App\Console\Commands\Product;

use App\Console\Services\InputService;
use App\Console\Services\ProductInputService;
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

    protected ProductInputService $productInputService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        ProductService      $productService,
        CategoryService     $categoryService,
        InputService        $inputService,
        UploadService       $uploadService,
        ProductInputService $productInputService
    )
    {
        parent::__construct();
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->inputService = $inputService;
        $this->uploadService = $uploadService;
        $this->productInputService = $productInputService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $productCategoriesChoices = $this->categoryService->getAllNamesAsArray();
        $inputs = $this->productInputService->askForInptus($this, $productCategoriesChoices);
        $inputs['categories'] = $this->categoryService->getIdsFromNames($inputs['categories']);

        try {
            $inputs['image'] = $this->uploadService->uploadExternalResource($inputs['imageSrc']);
            $this->productService->create($inputs);
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
