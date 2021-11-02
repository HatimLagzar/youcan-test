<?php

namespace App\Console\Commands\Category;

use App\Console\IOHelpers\InputHelper;
use App\Exceptions\DatabaseManipulationException;
use App\Services\CategoryService;
use Exception;
use Illuminate\Console\Command;

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

    protected InputHelper $inputHelper;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        CategoryService $categoryService,
        InputHelper $inputHelper
    )
    {
        parent::__construct();
        $this->categoryService = $categoryService;
        $this->inputHelper = $inputHelper;
    }

    /**
     * Execute the create category console command.
     */
    public function handle(): int
    {
        $name = $this->inputHelper->ask($this, 'Enter Category Name');
        $parentCategoryName = $this->choice(
            'Select Parent Category ID',
            ['None', ...$this->categoryService->getAllNamesAsArray()],
            0
        );

        if ($parentCategoryName !== 'None') {
            $parentCategory = $this->categoryService->findByName($parentCategoryName);
            if (!$parentCategory) {
                $this->error('Parent category not found in the database.');

                return Command::FAILURE;
            }
        }

        try {
            $this->categoryService->create($name, isset($parentCategory) ? $parentCategory->id : null);
            $this->info('Category created successfully.');

            return Command::SUCCESS;
        } catch (DatabaseManipulationException | Exception $exception) {
            $this->error($exception->getMessage());

            return Command::FAILURE;
        }
    }
}
