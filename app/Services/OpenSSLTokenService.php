<?php

namespace App\Services;

use App\Exceptions\ResourceException;

/**
 * openssl
 */
class OpenSSLTokenService
{
    /**
     * 密钥
     * @var string
     */
    private string $pass = '2der4yJc7pJySNE3bHIbg3M7snolteoz'; // Str::random(32)

    /**
     * iv
     * @var string
     */
    private string $iv = 'niItD46FbJ0kdPxg'; // Str::random(16)

    /**
     * 加密
     *
     * @param mixed $value
     * @param int $expires -1：永久有效
     * @param string $cipher_algo
     * @param int $options
     * @return bool|string
     */
    public function encrypt(
        mixed $value,
        int $expires = -1,
        string $cipher_algo = 'AES-256-CBC',
        int $options = 0
    ): bool|string
    {
        $value = json_encode([
            $value,
            time(),
            $expires
        ]);
        return openssl_encrypt($value, $cipher_algo, $this->pass, $options, $this->iv);
    }

    /**
     * 解密
     *
     * @param string $raw
     * @param string $cipher_algo
     * @param int $options
     * @return mixed
     * @throws ResourceException
     */
    public function decrypt(
        string $raw,
        string $cipher_algo = 'AES-256-CBC',
        int $options = 0
    ): mixed
    {
        $json = openssl_decrypt($raw, $cipher_algo, $this->pass, $options, $this->iv);
        [$value, $startTime, $expires] = json_decode($json, true);

        if ($expires > 0 && time() > $startTime + $expires) {
            throw new ResourceException(__('messages.verify_failed'));
        }

        return $value;
    }
}