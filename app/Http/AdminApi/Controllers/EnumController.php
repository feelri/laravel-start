<?php

namespace App\Http\AdminApi\Controllers;

use App\Services\ToolService;
use Illuminate\Http\JsonResponse;

/**
 * 枚举类型
 */
class EnumController extends Controller
{
	/**
	 * 列表
	 *
	 * @param string $type
	 * @return JsonResponse
	 */
	public function __invoke(string $type): JsonResponse
	{
		$enumClass = ToolService::static()->snakeToHump($type) . 'Enum';
		$enumClass = "\App\\Enums\\Model\\$enumClass";
		try {
			$values = $enumClass::collect(false, 'label');
		} catch (\Throwable) {
			$values = [];
		}
		return $this->response($values);
	}
}
