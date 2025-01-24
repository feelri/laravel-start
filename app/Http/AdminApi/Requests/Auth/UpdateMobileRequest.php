<?php

namespace App\Http\AdminApi\Requests\Auth;

use App\Http\Api\Requests\BaseRequest;
use App\Models\Admin\Admin;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateMobileRequest extends BaseRequest
{

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, ValidationRule|array|string>
	 */
	public function rules(): array
	{
		$adminClass = Admin::class;
		return [
			'mobile'   => "required|mobile|unique:{$adminClass}",
			'sms_code' => 'nullable|between:1,50',
		];
	}

	/**
	 * 字段别名
	 * @return array
	 */
	public function attributes(): array
	{
		return [
			'mobile'   => __('validation.attributes.mobile'),
			'sms_code' => __('validation.attributes.sms_code'),
		];
	}
}
