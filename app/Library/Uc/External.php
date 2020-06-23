<?php
/**
 * Created by PhpStorm.
 * User: huangxm
 * Date: 2018/10/12
 * Time: 5:57 PM
 */

namespace App\Library\Uc;


class External extends Abstracts
{
    /**
     * 外部联系人列表
     *
     * @var string
     */
    const LIST_URL = '%s/b/%s/qy/external/list';

    /**
     * 通过外部联系人id获取外部联系人详情
     *
     * @var string
     */
    const DETAIL_URL = '%s/b/%s/qy/external/detail';

    /**
     * 通过code获取员工外部联系人-关联企业客户库
     *
     * @var string
     */
    const CODE_URL = '%s/qy/external/get-by-code';

    /**
     * 同步某个员工的外部联系人
     *
     * @var string
     */
    const SYNC_URL = '%s/qy/external/sync';

    /**
     * 更新外部人员信息
     *
     * @var string
     */
    const UPD_URL = '%s/qy/external/update-by-unionid';

    /**
     * 获取企业微信userid/sessionkey
     *
     * @var string
     */
    const INF_URL = '%s/miniprogram/jscode2session';

    /**
     * 导入外部人员
     *
     * @var string
     */
    const IMPORT_URL = '%s/b/%s/qy/external/import';

    /**
     * 查询外部联系人总数
     *
     * @var string
     */
    const CONSUMER_URL = '%s/qy/external/number';

    /**
     * 获取外部联系人列表
     *
     * @param $qyDomain
     * @param array $params
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/15 3:33 PM
     */
    public function list($qyDomain, $params = [])
    {
        $url = vsprintf(self::LIST_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $params);
    }

    /**
     * 添加外部联系人
     *
     * @param $qyDomain
     * @param string $appIdentifier
     * @param array $condition
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/15 3:33 PM
     */
    public function import($qyDomain, $appIdentifier = '', $condition = [])
    {
        $url = vsprintf(self::IMPORT_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $condition);
    }

    /**
     * 获取外部联系人详情
     *
     * @param $qyDomain
     * @param array $params
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/15 3:33 PM
     */
    public function detail($qyDomain, $params = [])
    {
        $url = vsprintf(self::DETAIL_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $params);
    }
}