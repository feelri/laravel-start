<?php

namespace App\Http\UserApi\Requests\Auth;

use App\Http\Api\Requests\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class AuthRegisterRequest extends BaseRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'name'     => 'required|string',
			'nickname' => 'required|string',
			'avatar'   => 'required|string',
			'mobile'   => 'required|string',
			'code'     => 'required|string|verify:sms,loginCode',
		];
	}

	/**
	 * 字段别名
	 * @return array
	 */
	public function attributes(): array
	{
		return [
			'name'     => '真实姓名',
			'nickname' => '昵称',
			'avatar'   => '头像',
			'mobile'   => __('validation.attributes.mobile'),
			'code'     => '短信验证码',
		];
	}
}
