<?php

namespace App\Exceptions;

use App\Enums\HTTPStatusEnum;
use Exception;
use App\Enums\HTTPCodeEnum;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Throwable;

abstract class BaseException extends Exception implements ExceptionInterface
{
    use ResponseTrait;

    /**
     * constructor
     *
     * @param string         $message  消息
     * @param HTTPCodeEnum   $error    错误码
     * @param Throwable|null $previous 异常类
     * @param HTTPStatusEnum $status   HTTP status
     * @param string         $info     其他信息
     * @param string         $link     参考文档链接
     */
    public function __construct(
        /**
         * 消息
         * @var string
         */
        public $message = "",

        /**
         * HTTP 状态码
         * @var int
         */
        public HTTPCodeEnum $error = HTTPCodeEnum::Error,

        /**
         * 异常
         */
        public null|Throwable $previous = null,

        /**
         * 自定义参数 status（HTTP状态码）
         * @var HTTPStatusEnum
         */
        protected HTTPStatusEnum $status = HTTPStatusEnum::Error,

        /**
         * 自定义参数 info （其他信息）
         * @var string
         */
        protected string $info = "",

        /**
         * 自定义参数 link （帮助链接）
         * @var string
         */
        protected string $link = ""
    ) {

        parent::__construct(
            !empty($message) ? $message : $this->message,
            $error->value,
            $previous
        );
    }

    /**
     * 将异常渲染至 HTTP 响应值中
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return $this->fail(
            $this->message ?? $this->code->text(),
            null,
            $this->status,
            $this->error
        );
    }
}
