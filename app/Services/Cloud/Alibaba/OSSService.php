<?php

namespace App\Services\Cloud\Alibaba;

use App\Enums\Model\FileUploadFromEnum;
use App\Exceptions\ErrorException;
use App\Models\FileUpload;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use OSS\Core\OssException;
use OSS\Http\RequestCore_Exception;
use OSS\OssClient;
use AlibabaCloud\Client\Exception\ClientException;

class OSSService extends Alibaba
{
    /**
     * OSS 客户端
     * @var OssClient
     */
    public OssClient $client;

	/**
	 * constructor
	 *
	 * @throws ErrorException
	 * @throws ClientException
	 */
    public function __construct()
    {
        parent::__construct();

        try {
            $this->client = new OssClient(
                $this->config['accessKeyId'],
                $this->config['accessKeySecret'],
                $this->config['OSS']['endpoint']
            );
        } catch (\Exception $e) {
            throw new ErrorException(previous: $e);
        }
    }

    /**
     * 删除
     *
     * @param string $object
     * @return bool
     * @throws OssException
     * @throws RequestCore_Exception
     */
    public function delete(string $object): bool
    {
        $this->client->deleteObject($this->config['OSS']['bucket'], $object);
        return true;
    }

    /**
     * 字符串上传
     *
     * @param string $object
     * @param string $content
     * @return bool
     * @throws OssException
     * @throws RequestCore_Exception
     */
    public function uploadString(string $object, string $content): bool
    {
        $this->client->putObject($this->config['OSS']['bucket'], $object, $content);
        return true;
    }

    /**
     * 文件列表
     *
     * @param string $prefix
     * @param int    $max
     * @param bool   $isSelf
     * @return array
     * @throws OssException
     * @throws RequestCore_Exception
     */
    public function list(string $prefix, int $max = 1000, bool $isSelf = false): array
    {
        $result = $this->client->listObjectsV2($this->config['OSS']['bucket'], [
            'prefix'                => $prefix,
            OssClient::OSS_MAX_KEYS => $max
        ]);
        $data = [];
        foreach ($result->getObjectList() as $item) {
            $key = $item->getKey();
            if (!$isSelf && $key === $prefix) {
                continue;
            }

            $data[] = [
                'key'           =>  $key,
                'lastModified'  =>  $item->getLastModified(),
                'eTag'          =>  str_replace('"', '', $item->getETag()),
                'type'          =>  $item->getType(),
                'size'          =>  $item->getSize()
            ];
        }

        return $data;
    }

	/**
	 * 上传并保存文件
	 * TODO 该方法相当于两次上传，一次上传到本地，一次上传到 oss
	 *
	 * @param string $object
	 * @param string $content
	 * @return mixed
	 * @throws OssException
	 * @throws RequestCore_Exception
	 */
	public function uploadStringAndCreate(string $object, string $content): mixed
	{
		// TODO 保存图片到本地无法不保存文件的情况下获取元信息
		Storage::disk('uploads')->put($object, $content);
		$filepath = Storage::disk('uploads')->path($object);
		$file = new File($filepath);

		DB::beginTransaction();
		try {
			// 保存数据
			$data = [
				'from'          => FileUploadFromEnum::AliYun->value,
				'marker'        => md5_file($filepath),
				'name'          => $file->getFilename(),
				'mime'          => $file->getMimeType(),
				'suffix'        => $file->getExtension(),
				'url'           => $object,
				'size'          => $file->getSize(),
				'created_at'    => date("Y-m-d H:i:s")
			];
			$fileModel = FileUpload::query()->firstOrcreate(['marker' => $data['marker']], $data);
			if (!$fileModel->wasRecentlyCreated) {
				return $fileModel;
			}

			// 上传到 oss
			$this->uploadString($object, $content);

			DB::commit();
		}
		catch (\Exception $e) {
			DB::rollBack();
			throw $e;
		}
		finally {
			// 删除文件
			Storage::disk('uploads')->delete($object);
		}

		return $fileModel;
	}
}
