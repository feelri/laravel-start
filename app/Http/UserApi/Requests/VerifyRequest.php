<?php

namespace App\Http\UserApi\Requests;

use App\Http\Api\Requests\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class VerifyRequest extends BaseRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'mobile'       => 'required|string',
			'verify_token' => 'required|verify',
		];
	}

	/**
	 * 字段别名
	 * @return array
	 */
	public function attributes(): array
	{
		return [
			'mobile'       => __('validation.attributes.account'),
			'verify_token' => __('validation.attributes.verify_token'),
		];
	}
}
