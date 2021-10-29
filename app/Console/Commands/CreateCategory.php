<?php

namespace App\Console\Commands;

use App\Exceptions\DatabaseManipulationException;
use App\Services\CategoryService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class CreateCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a category';

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
     * Execute the create category console command.
     */
    public function handle(): int
    {
        $name = null;
        while (!$name) {
            $name = filter_var($this->ask('Enter Category Name'), FILTER_SANITIZE_STRING);
        }

        $choices = ['None', ...Arr::flatten($this->categoryService->getAll(['name'])->toArray())];
        $parentCategoryName = $this->choice(
            'Select Parent Category ID',
            $choices,
            0
        );

        if ($parentCategoryName === 'None') {
            $parentCategory = null;
        } else {
            $parentCategory = $this->categoryService->findByName($parentCategoryName);
        }

        if (!$parentCategory && $parentCategoryName !== 'None') {
            $this->error('Parent category not found in the database.');
            return Command::FAILURE;
        }

        try {
            $this->categoryService->create($name, $parentCategory ? $parentCategory->id : null);
            $this->info('Category created successfully.');
            return Command::SUCCESS;
        } catch (DatabaseManipulationException $exception) {
            $this->error($exception->getMessage());
            return Command::FAILURE;
        }
    }
}
