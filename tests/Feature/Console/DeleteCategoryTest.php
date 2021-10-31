<?php

namespace Tests\Feature\Console;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class DeleteCategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_category_console()
    {
        Category::factory()->count(10)->create();
        $category = Category::factory()->create();
        $choices = Arr::flatten(Category::all('name')->toArray());
        $this->artisan('category:delete')
            ->expectsChoice(
                'Select Category to be deleted',
                $category->name,
                $choices
            )
            ->assertExitCode(Command::SUCCESS);
    }
}
