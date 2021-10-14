<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Category
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property int $parent_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Category extends Model
{
	use HasFactory;

	protected $table = 'categories';
	protected $fillable = ['name', 'parent_id'];

	/**
	 * Get the parent category
	 */
	public function parentCategory()
	{
		return $this->hasOne(Category::class, 'id', 'parent_id');
	}
}
