<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->table(
            ['ID', 'Name', 'Price'],
            Product::all(['id', 'name', 'price'])->toArray()
        );

        $choices = Arr::flatten(Product::all('name')->toArray());
        if (count($choices) === 0) {
            $this->info('0 products found.');
            return Command::FAILURE;
        }

        $productNameToBeDeleted = $this->choice(
            'Select the product that you want to delete',
            $choices
        );

        $product = Product::where('name', $productNameToBeDeleted)->first();
        if (! $product) {
            $this->error('Product not found.');
            return Command::FAILURE;
        }

        if ($product->delete()) {
            $this->info('Product deleted successfully.');
            return Command::SUCCESS;
        } else {
            $this->error('Unknown error occured while deleting the product.');
            return Command::FAILURE;
        }
    }
}
