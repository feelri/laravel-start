<?php

namespace App\Models;

class Config extends Model
{
	/**
	 * 类型转换
	 * @return string[]
	 */
	protected function casts(): array
	{
		return [
			...parent::casts(),
			'value' => 'json',
		];
	}
}
