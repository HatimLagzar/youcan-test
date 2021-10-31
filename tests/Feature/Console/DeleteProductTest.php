<?php

namespace Tests\Feature\Console;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class DeleteProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_product_from_console()
    {
        Product::factory()->count(10)->create();
        $product = Product::factory()->create();
        $choices = Arr::flatten(Product::all('name')->toArray());
        $this->artisan('product:delete')
            ->expectsChoice(
                'Select the product that you want to delete',
                $product->name,
                $choices
            )
            ->assertExitCode(Command::SUCCESS);
    }
}
