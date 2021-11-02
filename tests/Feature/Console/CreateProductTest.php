<?php

namespace Tests\Feature\Console;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateProductTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_product_console()
    {
        $productCategoriesChoices = Category::all('name')->toArray();
        $productCategoriesChoices = ['None', ...$productCategoriesChoices];

        $this->artisan('product:create')
            ->expectsQuestion('Enter product name', 'T-Shirt')
            ->expectsQuestion('Enter product description', 'description')
            ->expectsQuestion('Enter product price (Number)', 39.99)
            ->expectsQuestion(
                'Enter product image, URL or local path',
                'https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png'
            )
            ->expectsChoice(
                'Select product categories, to use multiple categories use comma (Cat One, Cat Five)...',
                ['None'],
                $productCategoriesChoices
            )
            ->assertExitCode(Command::SUCCESS);
    }
}
