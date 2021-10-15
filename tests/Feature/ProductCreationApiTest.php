<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductCreationApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_product()
    {
        $response = $this->post('/api/products');
        $response->assertJsonFragment([
            'status' => 400    
        ]);

        $response = $this->post('/api/products', [
            'name' => 'Hello'
        ]);

        $response->assertJsonFragment([
            'status' => 400
        ]);

        $response = $this->post('/api/products', [
            'name' => 'Hello',
            'description' => 'Some text',
            'price' => 19.99,
            'image' => UploadedFile::fake()->image('dfsdhlkfh.png')
        ]);

        $response->assertJsonFragment(['status' => 200]);
    }
}
