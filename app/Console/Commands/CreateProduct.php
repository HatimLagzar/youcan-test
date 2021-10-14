<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
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
		$name = null;
		$description = null;
		$price = null;
		$image_src = null;

		while (! $name) {
			$name = filter_var($this->ask('Enter product name'), FILTER_SANITIZE_STRING);
		}

		while (! $description) {
			$description = filter_var($this->ask('Enter product description'), FILTER_SANITIZE_STRING);
		}

		while (! $price || ! is_numeric($price)) {
			$price = filter_var($this->ask('Enter product price (Number)'), FILTER_SANITIZE_NUMBER_FLOAT);
		}

		while (! $image_src) {
			$image_src = filter_var($this->ask('Enter product image, URL or local path'), FILTER_SANITIZE_URL);
		}

		$this->table(
			['ID', 'Name', 'Parent'],
			Category::all(['id', 'name', 'parent_id'])->toArray()
		);

		$choices = $this->choice(
			'Select product categories(IDs), to use multiple categories use comma (1, 3, 55)...',
			['None', ...Arr::flatten(Category::all(['id'])->toArray())],
			0,
			null,
			true
		);

		DB::beginTransaction();
		$product = new Product();
		$product->name = $name;
		$product->description = $description;
		$product->price = floatval($price);
		$product->image_src = $image_src;
		if (! $product->save()) {
			DB::rollBack();
			$this->error('Unknown error occured while saving the product.');
			return Command::FAILURE;
		}

		foreach ($choices as $choice) {
			if ($choice !== 'None') {
				$productCategory = new ProductCategory();
				$productCategory->product_id = $product->id;
				$productCategory->category_id = intval($choice);
				if (! $productCategory->save()) {
					DB::rollBack();
					$this->error('Unknown error occured while saving the categories.');
					return Command::FAILURE;
				}
			}
		}

		DB::commit();
		$this->info('Product created successfully.');
		return Command::SUCCESS;
	}
}
