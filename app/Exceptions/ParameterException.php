<?php

namespace App\Exceptions;

use App\Enums\HTTPCodeEnum;
use App\Enums\HTTPStatusEnum;
use Throwable;

class ParameterException extends BaseException
{
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
        string $message = "",

        /**
         * HTTP 状态码
         * @var int
         */
        HTTPCodeEnum $error = HTTPCodeEnum::ErrorParameter,

        /**
         * 异常
         */
        null|Throwable $previous = null,

        /**
         * 自定义参数 status（HTTP状态码）
         * @var HTTPStatusEnum
         */
        protected HTTPStatusEnum $status = HTTPStatusEnum::ParamBad,

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
		$message = empty($message) ? __('exception.parameter') : $message;
		parent::__construct($message, $error, $previous, $status, $info, $link);
    }
}
