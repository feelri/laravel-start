<?php

namespace App\Http\Api\Requests;

use App\Rules\BankAccountRule;
use App\Rules\IdCardRule;
use App\Rules\VerifyRule;
use \Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\InvokableValidationRule;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

class BaseRequest extends FormRequest
{
	/**
	 * 单个验证失败后停止
	 * @var bool
	 */
	protected $stopOnFirstFailure = true;

	/**
	 * Determine if the admin is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * 前置操作
	 *
	 * @return void
	 * @throws Exception
	 */
    public function prepareForValidation(): void
    {
        /**
         * 注册内置验证规则
         */
		ValidatorFacade::extend('bank_account', function($attribute, $value, $parameters, $validator) {
            $rule = new BankAccountRule();
            return $rule->passes($attribute, $value);
        });
		ValidatorFacade::extend('id_card', function($attribute, $value, $parameters, $validator) {
            $rule = new IdCardRule();
            return $rule->passes($attribute, $value);
        });
		ValidatorFacade::extend('mobile', function($attribute, $value, $parameters, $validator) {
            $rule = new IdCardRule();
            return $rule->passes($attribute, $value);
        });
		ValidatorFacade::extend('verify', function($attribute, $value, $parameters, $validator) {
			$rule = InvokableValidationRule::make(new VerifyRule());
			$rule->setValidator($validator);
			return $rule->passes($attribute, $value);
        });
    }
}
