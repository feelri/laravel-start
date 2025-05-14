<?php

namespace App\Enums\Model;

use App\Enums\CollectTrait;

/**
 * 分类类型
 */
enum CategoryTypeEnum: string
{
	use CollectTrait;

	case FileUpload = 'file_upload'; // 文件上传
	case Dictionary = 'dictionary'; // 字典
}
