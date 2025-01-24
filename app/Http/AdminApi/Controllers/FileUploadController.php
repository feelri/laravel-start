<?php

namespace App\Http\AdminApi\Controllers;

use App\Enums\PaginateEnum;
use App\Http\AdminApi\Requests\FileUpload\SaveRequest;
use App\Models\FileUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 文件上传
 */
class FileUploadController extends Controller
{
	/**
	 * 列表
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function index(Request $request): JsonResponse
	{
		$params      = $request->only(['keyword', 'limit']);
		$fileUploads = FileUpload::query();
		if (!empty($params['keyword'])) {
			$fileUploads->where(function ($query) use ($params) {
				$query->where('name', 'like', "%{$params['keyword']}%");
			});
		}
		$fileUploads = $fileUploads->paginate($params['limit'] ?? PaginateEnum::Default->value);
		return $this->response($fileUploads);
	}

	/**
	 * 详情
	 *
	 * @param FileUpload $fileUpload
	 * @return JsonResponse
	 */
	public function show(FileUpload $fileUpload): JsonResponse
	{
		return $this->response($fileUpload);
	}

	/**
	 * 修改
	 *
	 * @param SaveRequest $request
	 * @param FileUpload $fileUpload
	 * @return JsonResponse
	 */
	public function update(SaveRequest $request, FileUpload $fileUpload): JsonResponse
	{
		$params = $request->only(['name']);
		$fileUpload->name = $params['name'];
		$fileUpload->save();
		return $this->success();
	}

	/**
	 * 删除
	 *
	 * @param FileUpload $fileUpload
	 * @return JsonResponse
	 */
	public function destroy(FileUpload $fileUpload): JsonResponse
	{
		$fileUpload->delete();
		return $this->success();
	}
}
