<?php

namespace App\Models;

class SmsLog extends Model
{
	/**
	 * 类型转换
	 * @return string[]
	 */
	protected function casts(): array
	{
		return [
			...parent::casts(),
			'request'  => 'array',
			'response' => 'array',
		];
	}
}