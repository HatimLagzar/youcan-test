<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

class DeleteCategory extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'category:delete {categoryId}';

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
		$categoryId = intval($this->argument('categoryId'));
		$category = Category::find($categoryId);
		if ($category) {
			if ($category->delete()) {
				$this->info('Category deleted successfully.');
				$this->table(
					['id', 'name', 'parent id'],
					Category::all(['id', 'name', 'parent_id'])->toArray()
				);
				return Command::SUCCESS;
			}
			else {
				$this->error('Unknown error occured while deleting the category, please retry later.');
				return Command::FAILURE;
			}
		}
		else {
			$this->table(
				['id', 'name', 'parent id'],
				Category::all(['id', 'name', 'parent_id'])->toArray()
			);
			$this->error('Category not found.');
			return Command::FAILURE;
		}
	}
}
