<?php

namespace App\Services\Upload;

use App\Enums\Model\FileUploadFromEnum;
use App\Exceptions\ErrorException;
use App\Models\FileUpload;

class Local implements AsyncUploadInterface
{
	/**
	 * 上传凭证
	 * @return array
	 */
	public function credentials(): array
	{
		return [];
	}

	/**
	 * 上传回调
	 *
	 * @param array $params
	 * @return FileUpload
	 * @throws ErrorException
	 */
	public function callback(array $params): FileUpload
	{
		$pathInfo = pathinfo($params['origin_name']);

		// 文件标识
		$marker = strtoupper($params['etag']);

		// 写入数据库
		$file = FileUpload::query()->firstOrCreate(
			[
				'marker' => $marker
			],
			[
				'from'       => FileUploadFromEnum::Local->value,
				'marker'     => $marker,
				'name'       => $params['origin_name'],
				'mime'       => $params['mimeType'],
				'suffix'     => $pathInfo['extension'] ?? '',
				'path'       => $params['object'],
				'size'       => $params['size'],
				'created_at' => date("Y-m-d H:i:s")
			]
		);
		if (!$file) {
			throw new ErrorException(__('messages.file_write_failed'));
		}

		return $file;
	}
}