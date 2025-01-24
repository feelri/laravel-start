<?php

namespace App\Http\AdminApi\Requests\FileUpload;

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
			'name'       => 'required|between:1,50',
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
		];
	}
}
