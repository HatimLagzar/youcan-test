<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductCategory
 * @package App\Models
 * @property int $id
 * @property int $product_id
 * @property int $category_id
 */
class ProductCategory extends Model
{
	use HasFactory;

	protected $table = 'products_categories';
	public $timestamps = false;
}
