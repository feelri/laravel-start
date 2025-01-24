<?php

return [
	'alibaba'   => [
		"accessKeyId"     => env('ALIBABA_ACCESS_KEY_ID', ''),
		"accessKeySecret" => env('ALIBABA_ACCESS_KEY_SECRET', ''),
		"region"          => env('ALIBABA_REGION', ''),

		/**
		 * RAM 账号 arn
		 */
		"roleArn"         => env('ALIBABA_ROLE_ARN', ''),

		/**
		 * 对象存储
		 */
		"OSS"             => [
			"endpoint"   => env('ALIBABA_OSS_ENDPOINT', ''),
			"bucket"     => env('ALIBABA_OSS_BUCKET', ''),
			"uploadPath" => env('ALIBABA_OSS_UPLOAD_PATH', "uploads/" . date("Y/m/d/")),
			"staticUrl"  => env("ALIBABA_OSS_STATIC_URL")
		],

		/**
		 * 短信
		 */
		"SMS"             => [
			"sign" => "",
		],

		/**
		 * 钉钉
		 */
		"dingTalk"        => [
			'webHookUrl'    => env('ALIBABA_DING_TALK_WEBHOOK_URL', ''),
			'webHookSecret' => env('ALIBABA_DING_TALK_SECRET', ''),
		],
	],
	'tencent'   => [
		"secretId"  => env('TENCENT_CLOUD_SECRET_ID', ''),
		"secretKey" => env('TENCENT_CLOUD_SECRET_KEY', ''),
		"region"    => env('TENCENT_CLOUD_REGION', 'ap-nanjing'),
		"sdkAppId"  => env('TENCENT_CLOUD_SDK_APP_ID', ''),
		"wechat"    => [
			"miniProgram" => [
				'app_id'                  => env('TENCENT_WECHAT_MINI_PROGRAM_APP_ID', ''),
				'secret'                  => env('TENCENT_WECHAT_MINI_PROGRAM_SECRET', ''),
				'token'                   => env('TENCENT_WECHAT_MINI_PROGRAM_TOKEN', env('APP_NAME', 'Laravel')),
				'aes_key'                 => env('TENCENT_WECHAT_MINI_PROGRAM_AES_KEY', ''), // 明文模式请勿填写 EncodingAESKey

				/**
				 * 是否使用 Stable Access Token
				 * 默认 false
				 * https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/mp-access-token/getStableAccessToken.html
				 * true 使用 false 不使用
				 */
				'use_stable_access_token' => false,

				/**
				 * 接口请求相关配置，超时时间等，具体可用参数请参考：
				 * https://github.com/symfony/symfony/blob/5.3/src/Symfony/Contracts/HttpClient/HttpClientInterface.php
				 */
				'http'                    => [
					'throw'   => true, // 状态码非 200、300 时是否抛出异常，默认为开启
					'timeout' => 5.0,
					// 'base_uri' => 'https://api.weixin.qq.com/', // 如果你在国外想要覆盖默认的 url 的时候才使用，根据不同的模块配置不同的 uri

					'retry' => true, // 使用默认重试配置
					//  'retry' => [
					//      // 仅以下状态码重试
					//      'status_codes' => [429, 500]
					//       // 最大重试次数
					//      'max_retries' => 3,
					//      // 请求间隔 (毫秒)
					//      'delay' => 1000,
					//      // 如果设置，每次重试的等待时间都会增加这个系数
					//      // (例如. 首次:1000ms; 第二次: 3 * 1000ms; etc.)
					//      'multiplier' => 3
					//  ],
				],
			],
		],
	],
	'huawei'    => [
		"accessKey" => env('HUAWEI_ACCESS_KEY', ''),
		"secretKey" => env('HUAWEI_SECRET_KEY', ''),
		"endpoint"  => env('HUAWEI_ENDPOINT', ''),
		"domainId"  => env('HUAWEI_DOMAIN_ID', ''),
	],
	'qiniu'     => [
		"accessKey" => env('QIU_NIU_ACCESS_KEY', ''),
		"secretKey" => env('QIU_NIU_SECRET_KEY', ''),
		"koDo"      => [
			"expires"     => env('QIU_NIU_KO_DO_EXPIRES', 3600),
			"bucket"      => env('QIU_NIU_KO_DO_BUCKET'),
			"uploadPath"  => env("QIU_NIU_KO_DO_UPLOAD_PATH", "chat/uploads/" . date("Y/m/d/")),
			"callbackUrl" => env("QIU_NIU_KO_DO_UPLOAD_CALLBACK_URL"),
			"staticUrl"   => env("QIU_NIU_KO_DO_STATIC_URL")
		]
	],
	'bytedance' => [

		/**
		 * 飞书
		 */
		'feishu' => [
			'webHookUrl'    => env('BYTEDANCE_FEISHU_WEBHOOK_URL', ''),
			'webHookSecret' => env('BYTEDANCE_FEISHU_WEBHOOK_SECRET', ''),
		],
	]
];
