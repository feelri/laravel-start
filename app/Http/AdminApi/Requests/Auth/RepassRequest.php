<?php

namespace App\Http\AdminApi\Requests\Auth;

use App\Http\Api\Requests\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class RepassRequest extends BaseRequest
{

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'old_password'   => "required|between:6,12|current_password",
			'new_password'   => "required|between:6,12",
		];
	}

	/**
	 * 字段别名
	 * @return array
	 */
	public function attributes(): array
	{
		return [
			'old_password'       => __('validation.attributes.old_password'),
			'new_password'   => __('validation.attributes.new_password'),
		];
	}
}
