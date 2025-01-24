<?php

namespace App\Http\AdminApi\Requests\Auth;

use App\Enums\BoolIntEnum;
use App\Http\Api\Requests\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateMeRequest extends BaseRequest
{

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, ValidationRule|array|string>
	 */
	public function rules(): array
	{
		$boolImplode = BoolIntEnum::implode();
		return [
			'name'       => 'required|between:1,50',
			'nickname'   => 'nullable|between:1,50',
			'avatar'     => 'nullable|url',
			'gender'     => "nullable|in:{$boolImplode}",
		];
	}

	/**
	 * 字段别名
	 * @return array
	 */
	public function attributes(): array
	{
		return [
			'name'       => __('validation.attributes.name'),
			'nickname'   => __('validation.attributes.nickname'),
			'avatar'     => __('validation.attributes.avatar'),
			'gender'     => __('validation.attributes.gender'),
		];
	}
}
