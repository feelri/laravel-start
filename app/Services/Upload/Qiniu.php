<?php

namespace App\Services\Upload;

use App\Enums\Model\FileUploadFromEnum;
use App\Exceptions\ErrorException;
use App\Models\FileUpload;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Qiniu\Auth;

class Qiniu extends \App\Services\Cloud\Qiniu\Qiniu implements AsyncUploadInterface
{
	/**
	 * 授权信息
	 * @return array
	 */
	public function credentials(): array
	{
		$auth   = new Auth($this->config['accessKey'], $this->config['secretKey']);
		$policy = $this->policy();

		$upToken = $auth->uploadToken(
			$this->config['koDo']['bucket'],
			null,
			$this->config['koDo']['expires'],
			$policy
		);
		return [
			'token' => $upToken
		];
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
				'from'       => FileUploadFromEnum::QiNiu->value,
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


	/**
	 * 上传策略
	 *
	 * @param array $params
	 * @return string[]
	 */
	private function policy(array $params = []): array
	{
		$host = Config::get('app.url');
		return [
			'saveKey'          => $this->config['uploadPath'] . Str::uuid() . '$(ext)',
			'callbackUrl'      => $host . ($params['callbackUrl'] ?? $this->config['callbackUrl']),
			'callbackBody'     => '{"bucket":"$(bucket)","etag":"$(etag)","mimeType":"$(mimeType)","object":"$(key)","size":$(fsize),"origin_name":"$(fname)"}',
			'callbackBodyType' => 'application/json'
		];
	}
}
