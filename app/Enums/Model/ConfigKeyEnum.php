<?php

namespace App\Enums\Model;

use App\Enums\CollectTrait;

/**
 * 配置 key
 */
enum ConfigKeyEnum: string
{
	use CollectTrait;

	case Basic = 'basic'; // 基础设置
	case System = 'system'; // 系统设置
	case FileUpload = 'system.file_upload'; // 文件上传
	case Sms = 'system.sms'; // 短信
	case Notice = 'system.notice'; // 通知
	case UserIncrement = 'user_increment'; // 用户自增编号
	case AlibabaCloud = 'cloud.alibaba'; // 阿里巴巴
	case BytedanceCloud = 'cloud.bytedance'; // 字节跳动
	case HuaweiCloud = 'cloud.huawei'; // 华为
	case QiniuCloud = 'cloud.qiniu'; // 七牛
	case TencentCloud = 'cloud.tencent'; // 腾讯
}