<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| as the size rules. Feel free to tweak each of these messages here.
	|
	*/

	'accepted'             => ':attribute 字段必须被接受。',
	'accepted_if'          => '当 :other 为 :value 时，:attribute 字段必须被接受。',
	'active_url'           => ':attribute 字段必须是一个有效的 URL。',
	'after'                => ':attribute 字段必须是一个在 :date 之后的日期。',
	'after_or_equal'       => ':attribute 字段必须是一个在 :date 之后或等于 :date 的日期。',
	'alpha'                => ':attribute 字段只能包含字母。',
	'alpha_dash'           => ':attribute 字段只能包含字母、数字、破折号和下划线。',
	'alpha_num'            => ':attribute 字段只能包含字母和数字。',
	'array'                => ':attribute 字段必须是一个数组。',
	'ascii'                => ':attribute 字段只能包含单字节的字母数字字符和符号。',
	'before'               => ':attribute 字段必须是一个在 :date 之前的日期。',
	'before_or_equal'      => ':attribute 字段必须是一个在 :date 之前或等于 :date 的日期。',
	'between'              => [
		'array'   => ':attribute 字段必须有 :min 到 :max 个项目。',
		'file'    => ':attribute 字段必须在 :min 到 :max 个字节之间。',
		'numeric' => ':attribute 字段必须在 :min 到 :max 之间。',
		'string'  => ':attribute 字段必须在 :min 到 :max 个字符之间。',
	],
	'boolean'              => ':attribute 字段必须是 true 或 false。',
	'can'                  => ':attribute 字段包含一个未授权的值。',
	'confirmed'            => ':attribute 字段的确认不匹配。',
	'contains'             => ':attribute 字段缺少一个必需的值。',
	'current_password'     => '密码不正确。',
	'date'                 => ':attribute 字段必须是一个有效的日期。',
	'date_equals'          => ':attribute 字段必须是一个等于 :date 的日期。',
	'date_format'          => ':attribute 字段必须匹配格式 :format。',
	'decimal'              => ':attribute 字段必须有 :decimal 位小数。',
	'declined'             => ':attribute 字段必须被拒绝。',
	'declined_if'          => '当 :other 为 :value 时，:attribute 字段必须被拒绝。',
	'different'            => ':attribute 字段和 :other 必须不同。',
	'digits'               => ':attribute 字段必须是 :digits 位数字。',
	'digits_between'       => ':attribute 字段必须在 :min 和 :max 位数字之间。',
	'dimensions'           => ':attribute 字段的图像尺寸无效。',
	'distinct'             => ':attribute 字段有重复值。',
	'doesnt_end_with'      => ':attribute 字段必须不以以下之一结尾: :values。',
	'doesnt_start_with'    => ':attribute 字段必须不以以下之一开头: :values。',
	'email'                => ':attribute 字段必须是一个有效的电子邮件地址。',
	'ends_with'            => ':attribute 字段必须以以下之一结尾: :values。',
	'enum'                 => '所选的 :attribute 无效。',
	'exists'               => '所选的 :attribute 无效。',
	'extensions'           => ':attribute 字段必须具有以下扩展名之一: :values。',
	'file'                 => ':attribute 字段必须是一个文件。',
	'filled'               => ':attribute 字段必须有一个值。',
	'gt'                   => [
		'array'   => ':attribute 字段必须有超过 :value 个项目。',
		'file'    => ':attribute 字段必须大于 :value 千字节。',
		'numeric' => ':attribute 字段必须大于 :value。',
		'string'  => ':attribute 字段必须大于 :value 个字符。',
	],
	'gte'                  => [
		'array'   => ':attribute 字段必须有 :value 个或更多项目。',
		'file'    => ':attribute 字段必须大于或等于 :value 千字节。',
		'numeric' => ':attribute 字段必须大于或等于 :value。',
		'string'  => ':attribute 字段必须大于或等于 :value 个字符。',
	],
	'hex_color'            => ':attribute 字段必须是一个有效的十六进制颜色。',
	'image'                => ':attribute 字段必须是一个图像。',
	'in'                   => '所选的 :attribute 无效。',
	'in_array'             => ':attribute 字段必须在 :other 中存在。',
	'integer'              => ':attribute 字段必须是一个整数。',
	'ip'                   => ':attribute 字段必须是一个有效的 IP 地址。',
	'ipv4'                 => ':attribute 字段必须是一个有效的 IPv4 地址。',
	'ipv6'                 => ':attribute 字段必须是一个有效的 IPv6 地址。',
	'json'                 => ':attribute 字段必须是一个有效的 JSON 字符串。',
	'list'                 => ':attribute 字段必须是一个列表。',
	'lowercase'            => ':attribute 字段必须是小写字母。',
	'lt'                   => [
		'array'   => ':attribute 字段必须有少于 :value 个项目。',
		'file'    => ':attribute 字段必须小于 :value 千字节。',
		'numeric' => ':attribute 字段必须小于 :value。',
		'string'  => ':attribute 字段必须少于 :value 个字符。',
	],
	'lte'                  => [
		'array'   => ':attribute 字段不能有超过 :value 个项目。',
		'file'    => ':attribute 字段必须小于或等于 :value 千字节。',
		'numeric' => ':attribute 字段必须小于或等于 :value。',
		'string'  => ':attribute 字段必须少于或等于 :value 个字符。',
	],
	'mac_address'          => ':attribute 字段必须是一个有效的 MAC 地址。',
	'max'                  => [
		'array'   => ':attribute 字段不能有超过 :max 个项目。',
		'file'    => ':attribute 字段不能大于 :max 千字节。',
		'numeric' => ':attribute 字段不能大于 :max。',
		'string'  => ':attribute 字段不能大于 :max 个字符。',
	],
	'max_digits'           => ':attribute 字段不能有超过 :max 位数字。',
	'mimes'                => ':attribute 字段必须是类型为 :values 的文件。',
	'mimetypes'            => ':attribute 字段必须是类型为 :values 的文件。',
	'min'                  => [
		'array'   => ':attribute 字段必须至少有 :min 个项目。',
		'file'    => ':attribute 字段必须至少为 :min 千字节。',
		'numeric' => ':attribute 字段必须至少为 :min。',
		'string'  => ':attribute 字段必须至少为 :min 个字符。',
	],
	'min_digits'           => ':attribute 字段必须至少有 :min 位数字。',
	'missing'              => ':attribute 字段必须缺失。',
	'missing_if'           => '当 :other 为 :value 时，:attribute 字段必须缺失。',
	'missing_unless'       => '除非 :other 在 :values 中，:attribute 字段必须缺失。',
	'missing_with'         => '当 :values 存在时，:attribute 字段必须缺失。',
	'missing_with_all'     => '当 :values 存在时，:attribute 字段必须缺失。',
	'multiple_of'          => ':attribute 字段必须是 :value 的倍数。',
	'not_in'               => '所选的 :attribute 无效。',
	'not_regex'            => ':attribute 字段格式无效。',
	'numeric'              => ':attribute 字段必须是一个数字。',
	'password'             => [
		'letters'       => ':attribute 字段必须至少包含一个字母。',
		'mixed'         => ':attribute 字段必须至少包含一个大写字母和一个小写字母。',
		'numbers'       => ':attribute 字段必须至少包含一个数字。',
		'symbols'       => ':attribute 字段必须至少包含一个符号。',
		'uncompromised' => '给定的 :attribute 已出现在数据泄露中。请选用不同的 :attribute。',
	],
	'present'              => ':attribute 字段必须存在。',
	'present_if'           => '当 :other 为 :value 时，:attribute 字段必须存在。',
	'present_unless'       => '除非 :other 为 :value，:attribute 字段必须存在。',
	'present_with'         => '当 :values 存在时，:attribute 字段必须存在。',
	'present_with_all'     => '当 :values 存在时，:attribute 字段必须存在。',
	'prohibited'           => ':attribute 字段是禁止的。',
	'prohibited_if'        => '当 :other 为 :value 时，:attribute 字段是禁止的。',
	'prohibited_unless'    => '除非 :other 在 :values 中，:attribute 字段是禁止的。',
	'prohibits'            => ':attribute 字段禁止 :other 存在。',
	'regex'                => ':attribute 字段格式无效。',
	'required'             => ':attribute 字段是必填的。',
	'required_array_keys'  => ':attribute 字段必须包含以下条目: :values。',
	'required_if'          => '当 :other 为 :value 时，:attribute 字段是必填的。',
	'required_if_accepted' => '当 :other 被接受时，:attribute 字段是必填的。',
	'required_if_declined' => '当 :other 被拒绝时，:attribute 字段是必填的。',
	'required_unless'      => '除非 :other 在 :values 中，:attribute 字段是必填的。',
	'required_with'        => '当 :values 存在时，:attribute 字段是必填的。',
	'required_with_all'    => '当 :values 存在时，:attribute 字段是必填的。',
	'required_without'     => '当 :values 不存在时，:attribute 字段是必填的。',
	'required_without_all' => '当 :values 都不存在时，:attribute 字段是必填的。',
	'same'                 => ':attribute 字段必须匹配 :other。',
	'size'                 => [
		'array'   => ':attribute 字段必须包含 :size 个项目。',
		'file'    => ':attribute 字段必须为 :size 千字节。',
		'numeric' => ':attribute 字段必须为 :size。',
		'string'  => ':attribute 字段必须为 :size 个字符。',
	],
	'starts_with'          => ':attribute 字段必须以以下之一开头: :values。',
	'string'               => ':attribute 字段必须是一个字符串。',
	'timezone'             => ':attribute 字段必须是一个有效的时区。',
	'unique'               => ':attribute 已被占用。',
	'uploaded'             => ':attribute 上传失败。',
	'uppercase'            => ':attribute 字段必须是大写字母。',
	'url'                  => ':attribute 字段必须是一个有效的 URL。',
	'ulid'                 => ':attribute 字段必须是一个有效的 ULID。',
	'uuid'                 => ':attribute 字段必须是一个有效的 UUID。',

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom'       => [
		'attribute-name' => [
			'rule-name' => '自定义消息',
		],
	],
	'verify'       => ':attribute 校验未通过',
	'bank_account' => ':attribute 格式不正确',
	'id_card'      => ':attribute 格式不正确',
	'mobile'       => ':attribute 格式不正确',


	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap our attribute placeholder
	| with something more reader friendly such as "E-Mail Address" instead
	| of "email". This simply helps us make our message more expressive.
	|
	*/

	'attributes' => [
		'account'        => '账号',
		'email'          => '邮箱',
		'mobile'         => '手机号',
		'password'       => '密码',
		'old_password'   => '原密码',
		'new_password'   => '新密码',
		'verify_token'   => '人机验证',
		'name'           => '名称',
		'nickname'       => '昵称',
		'gender'         => '性别',
		'avatar'         => '头像',
		'role'           => '角色',
		'is_disable'     => '是否禁用',
		'description'    => '描述',
		'rank'           => '权重',
		'type'           => '类型',
		'permission_ids' => '权限组',
		'icon'           => '图标',
		'uri'            => '路由',
		'method'         => '请求方式',
		'component'      => '组件路径',
		'is_show'        => '是否显示',
		'verify_code'    => '验证码',
		'sms_code'       => '短信验证码',
		'parent_id'      => '父级编号',
		'file'           => '文件'
	],
];
