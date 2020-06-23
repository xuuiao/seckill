<?php
/**
 * Created by PhpStorm.
 * User: simple
 * Date: 2018/10/12
 * Time: 10:37 AM
 */

namespace App\Library\Uc;

use App\Exceptions\Error;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

abstract class Abstracts
{
    /**
     * GuzzleHttp\Client
     *
     * @var Client|null
     */
    protected $client = null;

    /**
     * Http request Content-Type
     *
     * @var array
     */
    protected $contentType = [
        'Content-Type' => 'application/json'
    ];

    /**
     * 初始化
     *
     * Abstracts constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * 获取UC api地址
     *
     * @return mixed
     * @throws \Exception
     */
    protected function getUcApiUrl()
    {
        $env = config('app.env');
        $apiUrl = config('uc_api');

        if (empty($apiUrl[$env])) {
            throw new \Exception('UC API url error');
        }

        return $apiUrl[$env];
    }

    /**
     * 发起POST请求
     *
     * @param $url
     * @param array $data
     * @return mixed
     * @throws Error
     */
    protected function post($url, $data = [])
    {
        $data = empty($data) ? '{}' : json_encode($data);

        $options = [
            'headers' => $this->contentType,
            'body' => $data
        ];

        try {
            $response = $this->client->post($url, $options);
            $data = $response->getBody();
        } catch (ClientException $clientException) {
            throw new Error(1000, $clientException->getMessage(), 500);
        }

        return $this->result($data);
    }

    /**
     * 待处理 ...
     *
     * @param $url
     * @param array $data
     */
    protected function get($url, $data = [])
    {
        // ...
    }

    /**
     * @param $data
     * @return mixed
     * @throws Error
     */
    private function result($data)
    {
        Log::debug('UC Response: '.$data);
        $array = json_decode($data, true);
        $requestId = empty($array['requestId']) ? 'NOT REQUEST ID' : $array['requestId'];

        // 接口响应成功，直接返回结果
        if ($array['code'] === 'SUCCESS') {
            $result = empty($array['data']) ? [] : $array['data'];
            // 防止返回结果不是数组形式报错
            if (is_array($result)) {
                $result['requestId'] = $requestId;
            }
            return $result;
        }

        Log::error('UC error: '.json_encode($array));
        $error = 'UC error '.$array['msg'].' requestId: '.$requestId;

        // 详细字段报错信息
        if (!empty($array['data'][0]['field'])) {
            $error .= ' field: '.$array['data'][0]['field'].' message: '.$array['data'][0]['message'];
        }

        throw new Error(1000, $error);
    }
}
