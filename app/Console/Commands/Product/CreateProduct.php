<?php

namespace App\Console\Commands\Product;

use App\Console\Services\CategoryConsoleService;
use App\Console\Services\InputService;
use App\Console\Services\ProductConsoleService;
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

    protected ProductConsoleService $productConsoleService;

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
        CategoryConsoleService $categoryConsoleService,
        ProductConsoleService  $productConsoleService
    )
    {
        parent::__construct();
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->inputService = $inputService;
        $this->uploadService = $uploadService;
        $this->categoryConsoleService = $categoryConsoleService;
        $this->productConsoleService = $productConsoleService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $inputs = $this->productConsoleService->askForInptus($this);
        $inputs['categories'] = $this->categoryConsoleService->getIdsFromNames($inputs['categories']);

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
