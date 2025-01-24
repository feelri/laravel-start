<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use SoftDeletes;
	use NodeTrait;

	protected $hidden = ['left', 'right', 'created_at', 'updated_at'];

	public function getLftName(): string
	{
		return 'left';
	}

	public function getRgtName(): string
	{
		return 'right';
	}

	public function getParentIdName(): string
	{
		return 'parent_id';
	}
}
