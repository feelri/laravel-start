<?php

use Illuminate\Filesystem\Filesystem;

return [
	'disable'        => env('CAPTCHA_DISABLE', false),
	'characters'     => ['3', '4', '6', '7', '8', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'k', 'm', 'n', 'p', 'r', 'u', 'x', 'y', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'M', 'N', 'P', 'R', 'U', 'X', 'Y'],
	'fontsDirectory' => public_path('source/captcha/fonts'),
	'driver'         => 'default',
	'default'        => [
		'length'      => 4,
		'width'       => 120,
		'height'      => 46,
		'quality'     => 90,
		'angle'       => 0,
		'lines'       => 1,
		'expire'      => 300,
		'fontColors'  => [
			'#f5222d',
			'#fa541c',
			'#fa8c16',
			'#faad14',
			'#fadb14',
			'#a0d911',
			'#52c41a',
			'#13c2c2',
			'#1677ff',
			'#2f54eb',
			'#722ed1',
			'#eb2f96'
		],
		//		'backgrounds' => [public_path('source/captcha/backgrounds')],
	],
	'math'           => [
		'length'  => 9,
		'width'   => 120,
		'height'  => 36,
		'quality' => 90,
		'math'    => true,
	],

	'flat'    => [
		'length'     => 6,
		'width'      => 160,
		'height'     => 46,
		'quality'    => 90,
		'lines'      => 6,
		'bgImage'    => true,
		'bgColor'    => '#ecf2f4',
		'fontColors' => ['#2c3e50', '#c0392b', '#16a085', '#c0392b', '#8e44ad', '#303f9f', '#f57c00', '#795548'],
		'contrast'   => -5,
	],
	'mini'    => [
		'length' => 3,
		'width'  => 60,
		'height' => 32,
	],
	'inverse' => [
		'length'    => 5,
		'width'     => 120,
		'height'    => 36,
		'quality'   => 90,
		'sensitive' => true,
		'angle'     => 12,
		'sharpen'   => 10,
		'blur'      => 2,
		'invert'    => true,
		'contrast'  => -5,
	]
];
