<?php

namespace App\Services\Upload;

use App\Enums\Model\FileUploadFromEnum;
use App\Exceptions\ErrorException;
use App\Models\FileUpload;
use App\Services\Cloud\Alibaba\AlibabaService;
use \Exception;

class Alibaba extends \App\Services\Cloud\Alibaba\Alibaba implements AsyncUploadInterface
{
	public function __construct()
	{
		parent::__construct();
		$this->config = $this->config['OSS'];
	}

	/**
	 * 授权信息
	 *
	 * @return array
	 * @throws Exception
	 */
	public function credentials(): array
	{
		$res  = (new AlibabaService)->getAssumeRole();
		$data = $res->toArray();

		// 获取 aliyun oss 配置
		$data['Credentials']['Region'] = $this->config['region'];
		$data['Credentials']['Bucket'] = $this->config['bucket'];

		return $data['Credentials'];
	}

	/**
	 * 回调
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
				'from'       => FileUploadFromEnum::AliYun->value,
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
