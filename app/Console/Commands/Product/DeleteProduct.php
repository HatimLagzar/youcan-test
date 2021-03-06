<?php

namespace App\Console\Commands\Product;

use App\Services\ProductService;
use Illuminate\Console\Command;

class DeleteProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a product';

    protected ProductService $productService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->productService = $productService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $choices = $this->productService->getAllNamesAsArray();
        if (count($choices) === 0) {
            $this->info('0 products found.');
            return Command::FAILURE;
        }

        $productNameToBeDeleted = $this->choice(
            'Select the product that you want to delete',
            $choices
        );

        $product = $this->productService->findByName($productNameToBeDeleted);
        if (! $product) {
            $this->error('Product not found.');
            return Command::FAILURE;
        }

        if ($this->productService->delete($product->id)) {
            $this->info('Product deleted successfully.');
            return Command::SUCCESS;
        } else {
            $this->error('Unknown error occured while deleting the product.');
            return Command::FAILURE;
        }
    }
}
