<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class DeleteCategory extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'category:delete';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete the category specified';

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
			['ID', 'Name', 'Parent'],
			Category::all('id', 'name', 'parent_id')->toArray()
		);

		$choices = Arr::flatten(Category::all('name')->toArray());
		$categoryName = $this->choice('Select Category ID to be deleted', $choices);
		$category = Category::where('name', $categoryName)->first();

		if (! $category) {
			$this->error('Category not found.');
			return Command::FAILURE;
		}

		$categoryId = $category->id;
		if ($category->delete()) {
			$this->info('Category deleted successfully.');
			$this->table(
				['ID', 'Name', 'Parent'],
				Category::all(['id', 'name', 'parent_id'])->toArray()
			);
			return Command::SUCCESS;
		}
		else {
			$this->error('Unknown error occured while deleting the category, please retry later.');
			return Command::FAILURE;
		}
	}
}
