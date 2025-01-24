<?php

namespace App\Http\Api\Controllers;

use App\Enums\Model\ConfigKeyEnum;
use App\Exceptions\ResourceException;
use App\Http\Api\Requests\FileUpload\StoreRequest;
use App\Services\Model\ConfigService;
use App\Services\Upload\UploadService;
use \Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 文件上传
 */
class FileUploadController extends Controller
{
	/**
	 * 上传凭证
	 *
	 * @return JsonResponse
	 * @throws Exception
	 */
	public function credentials(): JsonResponse
	{
		return $this->response((new UploadService())->credentials());
	}

	/**
	 * 上传回调
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function callback(Request $request): JsonResponse
	{
		$params = $request->only(['bucket', 'etag', 'mimeType', 'object', 'size', 'origin_name']);
		$file   = (new UploadService())->callback($params);
		return $this->response($file->setVisible(["id", "name", "suffix", "mime", "size", "url"]));
	}

	/**
	 * 本地上传
	 *
	 * @param StoreRequest $request
	 * @return JsonResponse
	 * @throws ResourceException
	 */
	public function store(StoreRequest $request): JsonResponse
	{
		$requestFile = $request->file('file');
		if (!$requestFile) {
			throw new ResourceException(__('messages.please_choose_file'));
		}

		// 文件驱动
		$diskName = ConfigService::static()->key(ConfigKeyEnum::FileUpload)->get('disk');
		$url      = str_replace(config('app.url'), '', config('filesystems.disks.' . $diskName)['url']) ?? '';

		// 文件 md5
		$etag     = hash_file('md5', $requestFile->getRealPath());
		$pathInfo = pathinfo($requestFile->getClientOriginalName());
		$fileName = $etag . '.' . $pathInfo['extension'];

		// 参数
		$params = [
			'origin_name' => $requestFile->getClientOriginalName(),
			'etag'        => $etag,
			'mimeType'    => $requestFile->getClientMimeType(),
			'object'      => "{$url}/{$fileName}",
			'size'        => $requestFile->getSize(),
		];
		$file   = (new UploadService())->callback($params);

		// 如果新增数据
		if ($file->wasRecentlyCreated) {
			$requestFile->storeAs($url, $fileName, $diskName);
		}
		return $this->response($file->setVisible(["id", "name", "suffix", "mime", "size", "path", "url"]));
	}
}
