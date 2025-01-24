<?php

return [
	'http'          => [
		'ok'                 => '成功',
		'created'            => '创建成功',
		'accepted'           => '已接收',
		'bad'                => '客户端请求错误',
		'unauthorized'       => '未授权',
		'payment'            => '未付款',
		'forbidden'          => '拒绝访问',
		'param_bad'          => '参数错误',
		'error'              => '服务错误',
		'not_found'          => '资源不存在',
		'method_not_allowed' => '请求方式不存在',
		'unavailable'        => '服务不可用',
	],
	'captcha'       => [
		'captcha'    => '图形验证码',
		'cloudflare' => 'Cloudflare人机验证',
	],
	'file_upload'   => [
		'local'  => '本地',
		'aliyun' => '阿里云',
		'qiniu'  => '七牛云',
	],
	'notify' => [
		'ding_talk' => '钉钉机器人',
		'feishu'    => '飞书机器人',
	],
	'permission'    => [
		'exists'     => '权限已存在，无需重复添加',
		'not'        => '权限不足，无法访问',
		'menu'       => '菜单',
		'permission' => '权限',
	],
	'sms'                  => [
		'alibaba' => '阿里云',
		'tencent' => '腾讯云',
		'huawei'  => '华为云',
		'qiniu'   => '七牛云',
	],
];
