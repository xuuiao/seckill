<?php
/**
 * Created by PhpStorm.
 * User: simple
 * Date: 2018/10/15
 * Time: 12:05 PM
 */

namespace App\Library\Uc;

class JsApi extends Abstracts
{
    /**
     * 获取签名接口地址
     *
     * @var string
     */
    const AGENT_CONFIG_URL = '%s/a/%s/%s/qy/js-api-signature/agent/get';

    /**
     * 获取签名接口地址
     *
     * @var string
     */
    const CONFIG_URL = '%s/a/%s/%s/qy/jssignature';

    /**
     * 获取JS签名，用于调起选人组件、个人页面等
     *
     * @param $qyDomain
     * @param $appIdentifier
     * @param array $condition
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/10/15 12:07 PM
     */
    public function agentConfig($qyDomain, $appIdentifier, $condition = [])
    {
        $url = vsprintf(self::AGENT_CONFIG_URL, [$this->getUcApiUrl(), $qyDomain, $appIdentifier]);
        return $this->post($url, $condition);
    }

    /**
     * 获取JS签名，用于分享、转发等
     *
     * @param $qyDomain
     * @param $appIdentifier
     * @param array $condition
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/10/15 12:07 PM
     */
    public function config($qyDomain, $appIdentifier, $condition = [])
    {
        $url = vsprintf(self::CONFIG_URL, [$this->getUcApiUrl(), $qyDomain, $appIdentifier]);
        return $this->post($url, $condition);
    }
}
