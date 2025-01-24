<?php

use App\Enums\Model\CaptchaEnum;
use App\Enums\Model\FileUploadFromEnum;
use App\Enums\Model\FileUploadSuffixEnum;
use App\Enums\Model\NotifyDriverEnum;
use App\Enums\Model\SmsDriverEnum;

$assetUrl = env('APP_ASSET_URL', env('APP_URL', 'http://localhost'));

return [
	/**
	 * 自定义配置
	 */
	// 上传驱动
	'file_upload' => [
		'driver'             => env('APP_FILE_UPLOAD_DRIVER', FileUploadFromEnum::Local->value),
		'allowed_extensions' => FileUploadSuffixEnum::values(),
		'disk'               => 'upload',
		'max_size'			 => '2', // 最大上传大小，单位 mb
	],

	// 附件 url 前缀
	'asset_url'          => $assetUrl,

	// 默认头像
	'avatar'             => $assetUrl . '/source/images/default-avatar.jpg',

	// 404 图片
	'not_found'          => $assetUrl . '/source/images/default-not_found.png',

	// 人机验证默认驱动 captcha|cloudflare
	'captcha'			 => env('APP_CAPTCHA', CaptchaEnum::Captcha->value),

	// 配置缓存有效期
	'config_cache_expire' => env('APP_CONFIG_CACHE_EXPIRE', 86400),

	// 字典缓存有效期
	'dictionary_cache_expire' => env('APP_DICTIONARY_CACHE_EXPIRE', 86400),

	/**
	 * 接口速率配置
	 */
	'limiter'	=> [
		'maxAttempts'	=> 600,
		'decayMinutes'	=> 1
	],

	/**
	 * 短信
	 */
	'sms'	=> [
		'driver'	=> SmsDriverEnum::Alibaba->value
	],

	/**
	 * 通知
	 */
	'notice'	=> [
		'exception_notice' => 1, // 异常错误通知
		'driver'           => NotifyDriverEnum::DingTalk->value
	],
];