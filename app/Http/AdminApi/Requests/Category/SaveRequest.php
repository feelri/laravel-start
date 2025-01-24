<?php

namespace App\Http\AdminApi\Requests\Category;

use App\Enums\Model\CategoryTypeEnum;
use App\Http\Api\Requests\BaseRequest;
use App\Models\Category;
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
		$categoryClass = Category::class;
		$typeImplode = CategoryTypeEnum::implode();

		return [
			'parent_id'  => "nullable|exclude_if:parent_id,0|exists:{$categoryClass},id",
			'type'       => "required|in:{$typeImplode}",
			'name'       => 'required|between:1,50',
			'icon'       => 'nullable|between:1,50',
			'rank'       => 'nullable|integer',
		];
	}

	/**
	 * 字段别名
	 * @return array
	 */
	public function attributes(): array
	{
		return [
			'parent_id'  => __('validation.attributes.parent_id'),
			'type'       => __('validation.attributes.type'),
			'name'       => __('validation.attributes.name'),
			'icon'       => __('validation.attributes.icon'),
			'rank'       => __('validation.attributes.rank'),
		];
	}
}
