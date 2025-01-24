<?php

namespace App\Http\AdminApi\Requests\Admin;

use App\Enums\BoolIntEnum;
use App\Http\Api\Requests\BaseRequest;
use App\Models\Admin\Admin;
use App\Models\Admin\Role;
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
		$adminClass  = Admin::class;
		$roleClass   = Role::class;
		$boolImplode = BoolIntEnum::implode();

		// 获取路由参数，判断是新增还是修改操作
		$admin    = request()->route('admin');
		$required = $admin ? 'nullable' : 'required';
		$unique   = Rule::unique($adminClass, 'mobile');
		if ($admin) {
			$unique->ignore($admin->id);
		}

		return [
			'account'    => ["required", "between:6,12", $unique],
			'mobile'     => ["nullable", "mobile", $unique],
			'password'   => "{$required}|between:6,12",
			'name'       => 'required|between:1,50',
			'nickname'   => 'nullable|between:1,50',
			'avatar'     => 'nullable|url',
			'gender'     => "nullable|in:{$boolImplode}",
			'is_disable' => "nullable|in:{$boolImplode}",
			'role_ids'   => "required|array",
			'role_ids.*' => "required|distinct|exists:{$roleClass},id",
		];
	}

	/**
	 * 字段别名
	 * @return array
	 */
	public function attributes(): array
	{
		return [
			'account'    => __('validation.attributes.account'),
			'mobile'     => __('validation.attributes.mobile'),
			'password'   => __('validation.attributes.password'),
			'name'       => __('validation.attributes.name'),
			'nickname'   => __('validation.attributes.nickname'),
			'avatar'     => __('validation.attributes.avatar'),
			'gender'     => __('validation.attributes.gender'),
			'roles'      => __('validation.attributes.role'),
			'is_disable' => __('validation.attributes.is_disable'),
			'role_ids'   => __('validation.attributes.role'),
			'role_ids.*' => __('validation.attributes.role'),
		];
	}
}
