<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateCategory extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'category:create {name} {parentId?}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a category';

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
	 * Execute the create category console command.
	 */
	public function handle()
	{
		$name = filter_var($this->argument('name'), FILTER_SANITIZE_STRING);
		$parentId = $this->argument('parentId');
		if ($parentId) {
			$parentId = intval($parentId);
			$parentCategory = Category::find($parentId);
			if (! $parentCategory) {
				$this->table(
					['id', 'name', 'parent id'],
					Category::all(['id', 'name', 'parent_id'])->toArray()
				);
				$this->error('Incorrect parent id, category not found.');

				return Command::FAILURE;
			}
		}

		$category = Category::create([
			'name' 				=> $name,
			'parent_id' 	=> $parentId
		]);

		if ($category) {
			$this->table(
				['id', 'name', 'parent id'],
				Category::all(['id', 'name', 'parent_id'])->toArray()
			);
			$this->info('Category created successfully.');
			return Command::SUCCESS;
		}
		else {
			$this->error('Unknown error occured while creating the category, please retry later.');
			return Command::FAILURE;
		}
	}
}
