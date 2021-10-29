<?php

namespace App\Console\Commands\Category;

use App\Services\CategoryService;
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

    protected CategoryService $categoryService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CategoryService $categoryService)
    {
        parent::__construct();
        $this->categoryService = $categoryService;
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
            $this->categoryService->getAll(['id', 'name', 'parent_id'])->toArray()
        );

        $choices = Arr::flatten($this->categoryService->getAll(['name'])->toArray());
        $categoryName = $this->choice('Select Category ID to be deleted', $choices);
        $category = $this->categoryService->findByName($categoryName);

        if (! $category) {
            $this->error('Category not found.');
            return Command::FAILURE;
        }

        if ($category->delete()) {
            $this->info('Category deleted successfully.');
            $this->table(
                ['ID', 'Name', 'Parent'],
                $this->categoryService->getAll(['id', 'name', 'parent_id'])->toArray()
            );
            return Command::SUCCESS;
        } else {
            $this->error('Unknown error occured while deleting the category, please retry later.');
            return Command::FAILURE;
        }
    }
}
