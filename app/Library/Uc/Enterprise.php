<?php
/**
 * Created by PhpStorm.
 * User: simple
 * Date: 2018/10/13
 * Time: 10:12 AM
 */

namespace App\Library\Uc;

class Enterprise extends Abstracts
{
    /**
     * 企业详情接口
     *
     * @var string
     */
    const DETAIL_URL = '%s/b/%s/enterprise/detail';

    /**
     * 企业列表接口
     *
     * @var string
     */
    const LIST_URL = '%s/s/enterprise/page-list';

    /**
     * 企业信息详情
     *
     * @var string
     */
    const SYSTEM_DETAIL_URL = '%s/s/enterprise/detail';

    /**
     * 获取企业详情
     *
     * @param string $qyDomain 企业域名
     * @param array $params 筛选条件
     * @return mixed
     * @throws \Exception
     * @author   陈朔  <chenshuo@vchangyi.com>
     * @date     2018/10/13 10:14 AM
     */
    public function detail($qyDomain, $params = [])
    {
        $url = vsprintf(self::DETAIL_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $params);
    }

    /**
     * 获取企业详情
     *
     * @param array $params 筛选条件
     * @return mixed
     * @throws \Exception
     * @author   陈朔  <chenshuo@vchangyi.com>
     * @date     2018/10/13 10:14 AM
     */
    public function list($params = [])
    {
        $url = vsprintf(self::LIST_URL, [$this->getUcApiUrl()]);
        return $this->post($url, $params);
    }

    /**
     * 根据crop_id 从uc 获取企业信息
     *
     * @param $corpId
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/15 3:37 PM
     */
    public function enterpriseDetail($corpId)
    {
        $url = vsprintf(self::SYSTEM_DETAIL_URL, [$this->getUcApiUrl()]);
        return $this->post($url, ['corpId' => $corpId]);
    }
}
