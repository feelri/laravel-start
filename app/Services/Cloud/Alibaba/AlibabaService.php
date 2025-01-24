<?php

namespace App\Services\Cloud\Alibaba;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Result\Result;
use Exception;

class AlibabaService extends Alibaba
{
    /**
     * 获取 aliyun 临时 AccessKey
     *
     * @param string $moduleName
     * @return Result
     * @throws Exception
     */
    public function getAssumeRole(string $moduleName = 'know-that-admin'): Result
    {
        return AlibabaCloud::rpc()
            ->product('Sts')
            ->scheme('https')
            ->version('2015-04-01')
            ->action('AssumeRole')
            ->method('POST')
            ->host('sts.aliyuncs.com')
            ->options([
                'query' => [
                    'RegionId' => $this->config['region'],
                    'RoleArn' => $this->config['roleArn'],
                    'RoleSessionName' => $moduleName,
                ],
            ])
            ->request();
    }
}
