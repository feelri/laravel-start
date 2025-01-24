<?php

namespace App\Models;

use App\Traits\Model\ModelTrait;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
	use ModelTrait;

	protected $guarded = [];

	/**
	 * 类型转换
	 * @return string[]
	 */
	protected function casts(): array
	{
		return [
			'created_at' => 'datetime:Y-m-d H:i:s',
			'updated_at' => 'datetime:Y-m-d H:i:s',
			'deleted_at' => 'datetime:Y-m-d H:i:s',
		];
	}
}
