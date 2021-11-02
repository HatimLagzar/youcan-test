<?php

namespace App\Console\Commands\Category;

use App\Services\CategoryService;
use Illuminate\Console\Command;

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
        $choices = $this->categoryService->getAllNamesAsArray();
        $categoryName = $this->choice('Select Category to be deleted', $choices);
        $category = $this->categoryService->findByName($categoryName);

        if (!$category) {
            $this->error('Category not found.');

            return Command::FAILURE;
        }

        if ($this->categoryService->delete($category->id)) {
            $this->info('Category deleted successfully.');

            return Command::SUCCESS;
        } else {
            $this->error('Unknown error occured while deleting the category, please retry later.');

            return Command::FAILURE;
        }
    }
}
