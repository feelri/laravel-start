<?php

namespace App\Models;

use App\Traits\Model\ModelTrait;
use DateTimeInterface;
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
		return [];
	}


	/**
	 * 准备日期以进行数组/JSON 序列化。
	 * @param DateTimeInterface $date
	 * @return string
	 */
	protected function serializeDate(DateTimeInterface $date): string
	{
		return $date->format('Y-m-d H:i:s');
	}
}
