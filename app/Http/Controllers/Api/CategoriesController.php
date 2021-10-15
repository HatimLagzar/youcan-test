<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function all()
    {
        return response([
            'status' => 200,
            'msg' => 'Categories pulled successfully.',
            'categories' => Category::all()
        ]);
    }
}
