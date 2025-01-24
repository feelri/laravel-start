<?php

namespace App\Http\Api\Controllers;

use App\Enums\Model\ConfigKeyEnum;
use App\Services\Model\ConfigService;
use \Exception;
use Illuminate\Http\JsonResponse;
use Mews\Captcha\Captcha;

/**
 * 图形验证码
 */
class CaptchaController extends Controller
{
	/**
	 * index
	 *
	 * @param Captcha $captcha
	 * @return JsonResponse
	 * @throws Exception
	 */
	public function index(Captcha $captcha): JsonResponse
	{
		$driver = ConfigService::static()->key(ConfigKeyEnum::System)->get('captcha');
		if ($driver !== 'captcha') {
			throw new \Exception(__('messages.driver_not_enabled', ['driver'=>__('enum.captcha.captcha')]));
		}

		$data = $captcha->create(config('captcha.driver'), true);
		return $this->response($data);
	}
}