<?php

namespace App\Services\Upload;

use App\Enums\Model\ConfigKeyEnum;
use App\Enums\Model\FileUploadFromEnum;
use App\Models\FileUpload;
use App\Services\Model\ConfigService;
use Exception;

class UploadService implements AsyncUploadInterface
{
	/**
	 * 驱动
	 * @var AsyncUploadInterface
	 */
	private AsyncUploadInterface $driver;

	/**
	 * 驱动名称
	 * @var string|mixed
	 */
	private string $driverName;

	/**
	 * constructor
	 * @throws Exception
	 */
	public function __construct()
	{
		$driver = ConfigService::static()->key(ConfigKeyEnum::System)->get('file_upload.driver');
		if (empty($driver)) {
			throw new \Exception('未设置上传驱动');
		}
		$this->driverName = $driver;

		$driver = FileUploadFromEnum::tryFrom($driver)->driverClass();
		$this->driver = new $driver;
	}

	/**
	 * 授权信息
	 * @return array
	 * @throws Exception
	 */
	public function credentials(): array
	{
		return [
			'driver'    => $this->driverName,
			'data'      => $this->driver->credentials()
		];
	}

	/**
	 * 回调
	 *
	 * @param array $params
	 * @return FileUpload
	 */
	public function callback(array $params): FileUpload
	{
		return $this->driver->callback($params);
	}
}
