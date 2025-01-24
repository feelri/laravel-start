<?php

namespace App\Http\AdminApi\Requests\Permission;

use App\Enums\BoolIntEnum;
use App\Enums\Model\PermissionTypeEnum;
use App\Http\Api\Requests\BaseRequest;
use App\Models\Permission\Permission;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class SaveRequest extends BaseRequest
{

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, ValidationRule|array|string>
	 */
	public function rules(): array
	{
		$permissionClass = Permission::class;
		$typeImplode     = PermissionTypeEnum::implode();
		$boolImplode     = BoolIntEnum::implode();
		$typeMenu = PermissionTypeEnum::Menu->value;
		$typePermission = PermissionTypeEnum::Permission->value;

		return [
			'parent_id'  => "nullable|exclude_if:parent_id,0|exists:{$permissionClass},id",
			'type'       => "required|in:{$typeImplode}",
			'name'       => 'required|between:1,50',
			'icon'       => 'nullable|between:1,50',
			'path'       => "required_if:type,{$typeMenu}|between:1,100",
			'component'  => [
				Rule::requiredIf(request()->input('type') === $typeMenu && request()->input('parent_id')),
				"between:1,100",
			],
			'uri'        => "required_if:type,{$typePermission}|between:1,100",
			'method'     => "required_if:type,{$typePermission}|between:1,20",
			'is_show'    => "nullable|in:{$boolImplode}",
			'is_disable' => "nullable|in:{$boolImplode}",
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
			'path'       => __('validation.attributes.path'),
			'icon'       => __('validation.attributes.icon'),
			'uri'        => __('validation.attributes.uri'),
			'method'     => __('validation.attributes.method'),
			'component'  => __('validation.attributes.component'),
			'is_show'    => __('validation.attributes.is_show'),
			'is_disable' => __('validation.attributes.is_disable'),
			'rank'       => __('validation.attributes.rank'),
		];
	}
}
