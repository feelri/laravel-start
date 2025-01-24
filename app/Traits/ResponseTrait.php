<?php

namespace App\Traits;

use App\Enums\HTTPCodeEnum;
use App\Enums\HTTPStatusEnum;
use App\Enums\Model\ModelAliasEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ResponseTrait
{
    /**
     * json 相应
     *
     * @param mixed|null     $data    内容
     * @param string         $message 消息
     * @param HTTPStatusEnum $status  HTTP status
     * @param HTTPCodeEnum   $code    错误码
     * @return JsonResponse
     */
    public function response(
        mixed $data = null,
        string $message = '',
		HTTPStatusEnum $status = HTTPStatusEnum::Ok,
        HTTPCodeEnum $code = HTTPCodeEnum::Success
    ): JsonResponse
    {
        $return = [
            'code'      =>  $code->value,
            'message'   =>  empty($message) ? $status->label() : $message
        ];
        if ($data !== null) {
            $return['data'] = $data;
        }
        return response()->json($return, $status->value, [], JSON_BIGINT_AS_STRING);
    }

    /**
     * 成功
     *
     * @param string         $message
     * @param mixed|null     $data
     * @param HTTPStatusEnum $status
     * @param HTTPCodeEnum   $code
     * @return JsonResponse
     */
    public function success(
        string $message = '',
        mixed $data = null,
		HTTPStatusEnum $status = HTTPStatusEnum::Ok,
        HTTPCodeEnum $code = HTTPCodeEnum::Success
    ): JsonResponse
    {
		$message = empty($message) ? $status->label() : $message;
		return $this->response($data, $message, $status, $code);
    }

    /**
     * 失败
     *
     * @param string         $message
     * @param mixed|null     $data
     * @param HTTPStatusEnum $status
     * @param HTTPCodeEnum   $code
     * @return JsonResponse
     */
    public function fail(
        string $message = '',
        mixed $data = null,
		HTTPStatusEnum $status = HTTPStatusEnum::Error,
        HTTPCodeEnum $code = HTTPCodeEnum::Error
    ): JsonResponse
    {
		$message = empty($message) ? $status->label() : $message;
		return $this->response($data, $message, $status, $code);
    }

	/**
	 * 空相应
	 *
	 * @param HTTPStatusEnum $status
	 * @return Response
	 */
	public function noContent(HTTPStatusEnum $status = HTTPStatusEnum::Ok): Response
	{
		return response()->noContent($status->value);
	}
}
