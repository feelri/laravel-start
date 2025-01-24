<?php

namespace App\Enums\Model;

use App\Enums\CollectTrait;
use App\Services\Notice\DingTalk;
use App\Services\Notice\Feishu;

/**
 * 通知驱动
 */
enum NotifyDriverEnum: string
{
	use CollectTrait;

	case DingTalk = 'ding_talk';
	case Feishu   = 'feishu';

	/**
	 * 驱动上传类
	 * @return string
	 */
	public function driverClass(): string
	{
		return match ($this) {
			self::DingTalk => DingTalk::class,
			self::Feishu   => Feishu::class,
		};
	}

	/**
	 * 枚举文本转换
	 * @return string
	 */
	public function label(): string
	{
		return match ($this) {
			self::DingTalk => __('enum.notify.ding_talk'),
			self::Feishu => __('enum.notify.feishu'),
		};
	}
}