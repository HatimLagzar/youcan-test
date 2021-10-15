<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function all()
    {
        return response([
            'status' => 200,
            'msg' => 'Products pulled successfully.',
            'products' => Product::with(['categories'])->paginate(3)
        ]);
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'string|required',
            'description' => 'string|required',
            'price' => 'numeric|required',
            'image' => 'image|max:10000|required',
            'categories.*' => 'numeric|nullable'
        ]);

        if ($validation->fails()) {
            return response([
                'status' => 400,
                'msg' => $validation->errors()->first()
            ]);
        }

        $name = filter_var($request->input('name'), FILTER_SANITIZE_STRING);
        $description = filter_var($request->input('description'), FILTER_SANITIZE_STRING);
        $price = filter_var($request->input('price'), FILTER_SANITIZE_NUMBER_FLOAT);
        $price = floatval($price);
        $categories = filter_var_array($request->input('categories'), FILTER_SANITIZE_NUMBER_INT);
        $image = $request->file('image');

        DB::beginTransaction();
        $product = new Product();
        $product->name = $name;
        $product->description = $description;
        $product->price = $price;

        $imageSrc = $image->hashName();
        $image->storeAs('public/products/', $imageSrc);
        $product->image_src = $imageSrc;

        if (! $product->save()) {
            DB::rollBack();
            return response([
                'status' => 500,
                'msg' => 'Unknown error occurred while saving the product.'
            ]);
        }

        foreach ($categories as $categoryId) {
            $category = Category::find($categoryId);
            if ($category) {
                $productCategory = new ProductCategory();
                $productCategory->product_id = $product->id;
                $productCategory->category_id = $category->id;
                if (! $productCategory->save()) {
                    DB::rollBack();
                    return response([
                        'status' => 500,
                        'msg' => 'Unknown error occurred while saving the categories.'
                    ]);
                }
            }
        }

        DB::commit();
        return response([
            'status' => 200,
            'msg' => 'Product created successfully.',
            'product' => $product
        ]);
    }
}
