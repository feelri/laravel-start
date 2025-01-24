<?php

namespace App\Enums\Model;

use App\Enums\CollectTrait;

/**
 * 短信状态
 */
enum SmsStatusEnum: int
{
	use CollectTrait;

	case Succeed = 10;
	case Failed = 20;

	/**
	 * 枚举文本转换
	 * @return string
	 */
	public function label(): string
	{
		return match ($this) {
			self::Succeed => __('enum.sms.succeed'),
			self::Failed => __('enum.sms.failed'),
		};
	}
}