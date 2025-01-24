<?php

namespace App\Services\Model;

use App\Enums\Model\ConfigKeyEnum;
use App\Models\Config;
use App\Traits\StaticTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

/**
 * 配置服务
 */
class ConfigService
{
	use StaticTrait;

	/**
	 * 缓存前缀
	 * @var string
	 */
	public string $cachePrefix = 'config:';

	/**
	 * 缓存有效期（默认10天）
	 * @var int
	 */
	public int $cacheExpire = 864000;

	/**
	 * 是否从缓存读取
	 * @var bool
	 */
	public bool $isCache = true;

	/**
	 * 缓存 key
	 * @var ConfigKeyEnum
	 */
	public ConfigKeyEnum $key;

	/**
	 * constructor
	 */
	public function __construct(ConfigKeyEnum $key = null)
	{
		if ($key) {
			$this->key = $key;
		}

		// 获取缓存过期时间
		$this->cachePrefix = $this->key(ConfigKeyEnum::System)
			->get(
				'config_cache_expire',
				$this->getDefaultValue('config_cache_expire') ?? $this->cacheExpire
			);
	}

	/**
	 * 设置缓存key
	 *
	 * @param ConfigKeyEnum $key
	 * @return $this
	 */
	public function key(ConfigKeyEnum $key): static
	{
		$this->key = $key;
		return $this;
	}

	/**
	 * 是否从读取缓存
	 *
	 * @param bool $isCache
	 * @return $this
	 */
	public function isCache(bool $isCache): static
	{
		$this->isCache = $isCache;
		return $this;
	}

	/**
	 * 获取配置
	 *
	 * @param string|null $key
	 * @param mixed|null  $default
	 * @return mixed
	 */
	public function get(string $key = null, mixed $default = null): mixed
	{
		if (!$this->isCache || !$result = Cache::get("{$this->cachePrefix}:{$this->key->value}")) {
			$result = Config::query()->where('key', $this->key)->first();
			if ($result) {
				$result = $result->value;
				$this->isCache && Cache::set("{$this->cachePrefix}:{$this->key->value}", $result, $this->cacheExpire);
			}
		}

		// 兼容处理，替换默认配置值
		$defaultResult = $default ?? $this->getDefaultValue($key);
		$result = Arr::get($result, $key);
		if (is_array($result) && $defaultResult) {
			$result = array_merge($defaultResult, array_intersect_key($defaultResult, $result));
		}
		return empty($result) ? $defaultResult : $result;
	}

	/**
	 * 保存配置
	 *
	 * @param string|null $key
	 * @param array       $value
	 * @return bool
	 */
	public function set(string $key = null, mixed $value = null): bool
	{
		$config        = Config::query()->firstOrCreate(['key' => $this->key], ['name' => $key ?? $this->key, 'value' => []]);
		$defaultResult = $this->getDefaultValue($key);
		$configValue   = $config->value;
		$config->value = Arr::set($configValue, $key, array_intersect_key($defaultResult, $value));
		$config->save();
		$this->clear();

		return true;
	}

	/**
	 * 删除配置
	 * @return bool
	 */
	public function destroy(): bool
	{
		$config        = Config::query()->where('key', $this->key)->firstOrFail();
		$config->value = null;
		$config->save();
		return Cache::delete("{$this->cachePrefix}:{$this->key->value}");
	}

	/**
	 * 清除缓存
	 * @return bool
	 */
	public function clear(): bool
	{
		return Cache::delete("{$this->cachePrefix}:{$this->key->value}");
	}

	/**
	 * 获取默认值
	 *
	 * @param string|null $key
	 * @return mixed
	 */
	public function getDefaultValue(string $key = null): mixed
	{
		$dataKey = "cache.{$this->key->value}";
		if ($key) {
			$dataKey .= ".{$key}";
		}
		return \Illuminate\Support\Facades\Config::get($dataKey);
	}
}