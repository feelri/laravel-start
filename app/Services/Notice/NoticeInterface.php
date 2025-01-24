<?php

namespace App\Services\Notice;

interface NoticeInterface
{
	/**
	 * 发送通知
	 *
	 * @param array  $params
	 * @return array
	 */
	public function notify(array $params): array;
}
