<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Symfony\Component\Console\Command\Command as CommandAlias;

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
    public function handle(): int
    {
        $name = null;
        while (! $name) {
            $name = filter_var($this->ask('Enter Category Name'), FILTER_SANITIZE_STRING);
        }

        $this->table(
            ['ID', 'Name', 'Parent'],
            Category::all('id', 'name', 'parent_id')->toArray()
        );

        $choices = ['None', ...Arr::flatten(Category::all('name')->toArray())];
        $parentCategoryName = $this->choice(
            'Select Parent Category ID',
            $choices,
            0
        );

        if ($parentCategoryName === 'None') {
            $parentId = null;
        }

        $parentCategory = Category::where('name', $parentCategoryName)->first();
        if (! $parentCategory) {
            $this->error('Parent category not found in the database.');
            return Command::FAILURE;
        }

        if ($parentCategoryName !== 'None') {
            $parentId = $parentCategory->id;
        }

        $category = Category::create([
            'name' => $name,
            'parent_id' => $parentId
        ]);

        if ($category) {
            $this->table(
                ['id', 'name', 'parent id'],
                Category::all(['id', 'name', 'parent_id'])->toArray()
            );
            $this->info('Category created successfully.');
            return Command::SUCCESS;
        } else {
            $this->error('Unknown error occured while creating the category, please retry later.');
            return Command::FAILURE;
        }
    }
}
