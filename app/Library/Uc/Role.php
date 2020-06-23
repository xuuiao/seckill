<?php
/**
 * Created by PhpStorm.
 * User: huangxm
 * Date: 2018/10/14
 * Time: 2:53 PM
 */

namespace App\Library\Uc;

class Role extends Abstracts
{
    /**
     * 创建角色的接口地址
     *
     * @var string
     */
    const CREATE_URL = '%s/b/%s/qy/role/add';

    /**
     * 编辑角色的接口地址
     *
     * @var string
     */
    const MODIFY_URL = '%s/b/%s/qy/role/update';

    /**
     * 删除角色的接口地址
     *
     * @var string
     */
    const DELETE_URL = '%s/b/%s/qy/role/delete';

    /**
     * 获取角色列表的接口地址
     *
     * @var string
     */
    const LIST_URL = '%s/b/%s/qy/role/page-list';

    /**
     * 获取角色详情的接口地址
     *
     * @var string
     */
    const DETAIL_URL = '%s/b/%s/qy/role/get';

    /**
     * 添加角色
     *
     * @param $qyDomain
     * @param array $params
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/15 3:36 PM
     */
    public function add($qyDomain, $params = [])
    {
        $url = vsprintf(self::CREATE_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $params);
    }

    /**
     * 角色详情
     *
     * @param $qyDomain
     * @param array $params
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/15 3:36 PM
     */
    public function detail($qyDomain, $params = [])
    {
        $url = vsprintf(self::DETAIL_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $params);
    }

    /**
     * 角色列表
     *
     * @param $qyDomain
     * @param array $params
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/15 3:36 PM
     */
    public function list($qyDomain, $params = [])
    {
        $url = vsprintf(self::LIST_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $params);
    }

    /**
     * 角色删除
     *
     * @param $qyDomain
     * @param array $params
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/15 3:36 PM
     */
    public function delete($qyDomain, $params = [])
    {
        $url = vsprintf(self::DELETE_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $params);
    }

    /**
     * 编辑角色
     *
     * @param $qyDomain
     * @param array $params
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/15 3:36 PM
     */
    public function edit($qyDomain, $params = [])
    {
        $url = vsprintf(self::MODIFY_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $params);
    }
}