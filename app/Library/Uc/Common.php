<?php
/**
 * Created by PhpStorm.
 * User: simple
 * Date: 2018/10/13
 * Time: 2:40 PM
 */

namespace App\Library\Uc;

class Common extends Abstracts
{
    /**
     * 微信授权登录接口 传code值即可
     *
     * @var string
     */
    const AUTH_URL = '%s/a/%s/%s/qy/commondapi/wxuserlogin';

    /**
     * @param $qyDomain
     * @param $appIdentifier
     * @param array $params
     * @return mixed
     * @throws \App\Exceptions\Error
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/10/14 3:33 PM
     */
    public function authLogin($qyDomain, $appIdentifier, $params = [])
    {
        $url = vsprintf(self::AUTH_URL, [$this->getUcApiUrl(), $qyDomain, $appIdentifier]);
        return $this->post($url, $params);
    }
}
