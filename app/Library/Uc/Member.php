<?php
/**
 * Created by PhpStorm.
 * User: simple
 * Date: 2018/10/12
 * Time: 10:35 AM
 */

namespace App\Library\Uc;

class Member extends Abstracts
{
    /**
     * 增加员工接口
     *
     * @var string
     */
    const ADD_URL = '%s/a/%s/%s/qy/member/add';

    /**
     * 编辑员工接口
     *
     * @var string
     */
    const EDIT_URL = '%s/a/%s/%s/qy/member/update';

    /**
     * 员工列表接口
     *
     * @var string
     */
    const LIST_URL = '%s/a/%s/%s/qy/member/list';

    /**
     * 员工详情接口
     *
     * @var string
     */
    const DETAIL_URL = '%s/a/%s/%s/qy/member/get';

    /**
     * 删除员工
     *
     * @var string
     */
    const DELETE_URL = '%s/a/%s/%s/qy/member/delete';

    /**
     * 员工二维码
     *
     * @var string
     */
    const QR_CODE_URL = '%s/a/%s/%s/qy/member/qr-code/get';

    /**
     * 忽略权限查询人员列表
     *
     * @var string
     */
    const IGNORE_PERMISSIONS_LIST = '%s/a/%s/%s/qy/member/ignore-permissions/list';

    /**
     * 添加员工
     *
     * @param $qyDomain
     * @param $appIdentifier
     * @param array $condition
     * @return mixed
     * @throws \Exception
     */
    public function add($qyDomain, $appIdentifier, $condition = [])
    {
        $url = vsprintf(self::ADD_URL, [$this->getUcApiUrl(), $qyDomain, $appIdentifier]);
        return $this->post($url, $condition);
    }

    /**
     * 编辑员工
     *
     * @param $qyDomain
     * @param $appIdentifier
     * @param array $condition
     * @return mixed
     * @throws \Exception
     */
    public function edit($qyDomain, $appIdentifier, $condition = [])
    {
        $url = vsprintf(self::EDIT_URL, [$this->getUcApiUrl(), $qyDomain, $appIdentifier]);
        return $this->post($url, $condition);
    }

    /**
     * 员工列表
     *
     * @param $qyDomain
     * @param $appIdentifier
     * @param array $condition
     * @return mixed
     * @throws \Exception
     */
    public function list($qyDomain, $appIdentifier, $condition = [])
    {
        $url = vsprintf(self::LIST_URL, [$this->getUcApiUrl(), $qyDomain, $appIdentifier]);
        return $this->post($url, $condition);
    }

    /**
     * 员工详情
     *
     * @param $qyDomain
     * @param $appIdentifier
     * @param array $condition
     * @return mixed
     * @throws \Exception
     */
    public function detail($qyDomain, $appIdentifier, $condition = [])
    {
        $url = vsprintf(self::DETAIL_URL, [$this->getUcApiUrl(), $qyDomain, $appIdentifier]);
        return $this->post($url, $condition);
    }

    /**
     * 删除员工
     *
     * @param $qyDomain
     * @param $appIdentifier
     * @param array $condition
     * @return mixed
     * @throws \Exception
     */
    public function delete($qyDomain, $appIdentifier, $condition = [])
    {
        $url = vsprintf(self::DELETE_URL, [$this->getUcApiUrl(), $qyDomain, $appIdentifier]);
        return $this->post($url, $condition);
    }

    /**
     * 查询员工二维码
     *
     * @param $qyDomain
     * @param $appIdentifier
     * @param array $params
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/15 3:35 PM
     */
    public function qrCode($qyDomain, $appIdentifier, $params = [])
    {
        $url = vsprintf(self::QR_CODE_URL, [$this->getUcApiUrl(), $qyDomain, $appIdentifier]);
        return $this->post($url, $params);
    }

    /**
     * 忽略权限人员列表
     *
     * @param $qyDomain
     * @param $appIdentifier
     * @param array $params
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/15 3:35 PM
     */
    public function ignorePermissionsList($qyDomain, $appIdentifier, $params = [])
    {
        $url = vsprintf(self::IGNORE_PERMISSIONS_LIST, [$this->getUcApiUrl(), $qyDomain, $appIdentifier]);
        return $this->post($url, $params);
    }
}
