<?php

namespace App\Services\Model;

use App\Models\Dictionary\Dictionary;
use Illuminate\Support\Facades\Cache;

/**
 * 字典服务
 */
class DictionaryService
{
	/**
	 * 缓存前缀
	 * @var string
	 */
	public static string $cachePrefix = 'dictionary:';

	/**
	 * 缓存有效期
	 * @var int
	 */
	public static int $cacheExpire;

	/**
	 * constructor
	 */
	public function __construct()
	{
		self::$cacheExpire = \Illuminate\Support\Facades\Config::get('app.dictionary_cache_expire', 86400);
	}

	/**
	 * 获取
	 *
	 * @param string $type
	 * @param bool   $isCache
	 * @return array
	 */
	public static function get(string $type, bool $isCache = true): array
	{
		$cachePrefix = self::$cachePrefix;
		if (!$isCache || !$result = Cache::get("{$cachePrefix}:{$type}")) {
			$result = Dictionary::query()->where('type', $type)->firstOrFail()->toArray();
			$isCache && Cache::set("{$cachePrefix}:{$type}", $result, self::$cacheExpire);
		}
		return $result;
	}

	/**
	 * 保存
	 *
	 * @param string $type
	 * @param array  $value
	 * @return bool
	 */
	public static function set(string $type, array $value): bool
	{
		$cachePrefix = self::$cachePrefix;
		return Cache::set("{$cachePrefix}:{$type}", $value, self::$cacheExpire);
	}

	/**
	 * 删除
	 *
	 * @param string $type
	 * @return bool
	 */
	public static function destroy(string $type): bool
	{
		$cachePrefix = self::$cachePrefix;
		return Cache::delete("{$cachePrefix}:$type");
	}
}