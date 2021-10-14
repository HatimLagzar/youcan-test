<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
	public function all()
	{
		return response([
			'status' => 200,
			'msg' => 'Products pulled successfully.',
			'products' => Product::all()
		]);
	}
}
