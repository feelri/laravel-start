<?php

namespace App\Services;

use \Closure;
use \Exception;
use App\Traits\StaticTrait;
use Illuminate\Support\Facades\Redis;
use \Throwable;

/**
 * Redis 锁
 * 保证原子性
 */
class CacheLockService
{
	use StaticTrait;

    /**
     * 随机值
     * @var string
     */
    private string $key = 'lock-token';

	/**
	 * 异常
	 * @var Throwable
	 */
	public Throwable $exception;

    /**
     * 设置 key
     *
     * @param string $key
     * @return $this
     */
    public function setKey(string $key): CacheLockService
	{
        $this->key = $key;
        return $this;
    }

	/**
	 * 加锁
	 *
	 * @param string $token
	 * @param int    $expire 过期时长
	 * @return mixed
	 */
    public function lock(string $token = '1', int $expire = 10): mixed
	{
		return Redis::set($this->key, $token, "ex", $expire, "nx");
    }

	/**
	 * 解锁
	 *
	 * @param string $token
	 * @return mixed
	 */
    public function unlock(string $token = '1'): mixed
	{
        // LUA 脚本
        $script = "
            if redis.call('get',KEYS[1]) == ARGV[1]
            then
                return redis.call('del',KEYS[1])
            else
                return 0
            end
        ";

		return Redis::eval($script, 1, $this->key, $token);
    }

	/**
	 * 抛出异常
	 *
	 * @param Throwable|null $exception
	 * @return static
	 */
	public function throw(?Throwable $exception): static
	{
		$this->exception = $exception;
		return $this;
	}

	/**
	 * 执行上锁解锁
	 *
	 * @param Closure $callback
	 * @param string  $token
	 * @return mixed
	 * @throws Throwable
	 */
    public function transaction(Closure $callback, string $token = '1'): mixed
	{
		if (!$this->lock($token)) {
			throw $this->exception ?? new Exception(__('messages.operation_too_frequent'), 10001);
		}

		try {
			$result = $callback();
        }
		finally {
            $this->unlock($token);
        }

		return $result;
    }
}
