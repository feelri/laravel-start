<?php

namespace App\Services\Sms;

class HuaweiRequest
{
	public string $method  = '';
	public string $scheme  = '';
	public string $host    = '';
	public string $uri     = '';
	public array  $query   = [];
	public array  $headers = [];
	public string $body    = '';

	public function __construct()
	{
		$args = func_get_args();
		$i    = count($args);
		if ($i == 0) {
			$this->construct(null, null, null, null);
		} elseif ($i == 1) {
			$this->construct($args[0], null, null, null);
		} elseif ($i == 2) {
			$this->construct($args[0], $args[1], null, null);
		} elseif ($i == 3) {
			$this->construct($args[0], $args[1], $args[2], null);
		} else {
			$this->construct($args[0], $args[1], $args[2], $args[3]);
		}
	}

	public function construct($method, $url, $headers, $body): void
	{
		if ($method != null) {
			$this->method = $method;
		}
		if ($url != null) {
			$spl    = explode("://", $url, 2);
			$scheme = 'http';
			if (count($spl) > 1) {
				$scheme = $spl[0];
				$url    = $spl[1];
			}
			$spl   = explode("?", $url, 2);
			$url   = $spl[0];
			$query = [];
			if (count($spl) > 1) {
				foreach (explode("&", $spl[1]) as $kv) {
					$spl = explode("=", $kv, 2);
					$key = $spl[0];
					if (count($spl) == 1) {
						$value = "";
					} else {
						$value = $spl[1];
					}
					if ($key != "") {
						$key   = urldecode($key);
						$value = urldecode($value);
						if (array_key_exists($key, $query)) {
							array_push($query[$key], $value);
						} else {
							$query[$key] = [$value];
						}
					}
				}
			}
			$spl  = explode("/", $url, 2);
			$host = $spl[0];
			if (count($spl) == 1) {
				$url = "/";
			} else {
				$url = "/" . $spl[1];
			}
			$this->scheme = $scheme;
			$this->host   = $host;
			$this->uri    = urldecode($url);
			$this->query  = $query;
		}
		if ($headers != null) {
			$this->headers = $headers;
		}
		if ($body != null) {
			$this->body = $body;
		}
	}
}
