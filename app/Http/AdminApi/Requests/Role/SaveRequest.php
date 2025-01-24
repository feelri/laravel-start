<?php

namespace App\Http\AdminApi\Requests\Role;

use App\Http\Api\Requests\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class SaveRequest extends BaseRequest
{

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'name'             => 'required|between:1,50',
			'description'      => 'nullable|between:1,200',
			'rank'             => 'nullable|integer',
			'permission_ids'   => 'required|array',
			'permission_ids.*' => "required|distinct",
		];
	}

	/**
	 * 字段别名
	 * @return array
	 */
	public function attributes(): array
	{
		return [
			'name'             => __('validation.attributes.name'),
			'description'      => __('validation.attributes.description'),
			'rank'             => __('validation.attributes.rank'),
			'permission_ids'   => __('validation.attributes.permission_ids'),
			'permission_ids.*' => __('validation.attributes.permission_ids'),
		];
	}
}
