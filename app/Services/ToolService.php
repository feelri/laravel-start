<?php

namespace App\Services;

use App\Traits\StaticTrait;
use App\Traits\Tool\IterableTrait;
use App\Traits\Tool\StringTrait;
use App\Traits\Tool\TreeTrait;
use \Closure;

class ToolService
{
    /**
     * 多继承
     */
    use StaticTrait,
		TreeTrait,      // 树状结构
        StringTrait,    // 字符串
        IterableTrait   // 迭代数据
        ;

    /**
     * 字节转可读size
     * @param string $bytes
     * @return string
     */
    public function bytesToSize(string $bytes): string
    {
        $sizes = array('Bytes', 'KB', 'MB', 'GB', 'TB');
        $i = (int) floor(log($bytes) / log(1024));
        return round($bytes / pow(1024, $i), 2) . $sizes[$i];
    }

    /**
    * 获取用户首选语言
    *
    * 该方法解析 HTTP 头部中的 Accept-Language 字段，以确定用户的首选语言
    * 如果无法确定，则返回默认语言设置
    *
    * @param string $acceptLanguage HTTP 头部中的 Accept-Language 字段值
    * @param string $default 默认语言设置，当无法确定用户首选语言时使用
    * @return string 用户的首选语言列表，按用户偏好降序排列
    */
    public function getPreferredLanguage(string $acceptLanguage, string $default = 'zh-CN'): string
    {
        $languages = [];
        foreach (explode(',', $acceptLanguage) as $lang) {
            [$language, $quality] = array_merge(explode(';q=', $lang), [1]);
            $languages[$language] = (float) $quality;
        }
        arsort($languages);
        $languages = array_keys($languages);
        return $languages[0] ?? $default;
    }

	/**
	 * 抑制异常
	 *
	 * @param Closure      $callback
	 * @param mixed|null   $default
	 * @param Closure|null $errorHandler
	 * @return mixed
	 */
	public function ignoreException(Closure $callback, mixed $default = null, Closure $errorHandler = null): mixed
	{
		try {
			return $callback();
		} catch (\Throwable $e) {
			if ($errorHandler) {
				$errorHandler($e);
			}
		}
		return $default;
	}
}
