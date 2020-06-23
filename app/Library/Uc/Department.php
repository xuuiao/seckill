<?php
/**
 * Created by PhpStorm.
 * User: simple
 * Date: 2018/10/12
 * Time: 10:29 AM
 */

namespace App\Library\Uc;

class Department extends Abstracts
{
    /**
     * 创建部门的接口地址
     *
     * @var string
     */
    const CREATE_URL = '%s/a/%s/%s/qy/department/create';

    /**
     * 编辑部门的接口地址
     *
     * @var string
     */
    const MODIFY_URL = '%s/a/%s/%s/qy/department/modify';

    /**
     * 删除部门的接口地址
     *
     * @var string
     */
    const DELETE_URL = '%s/a/%s/%s/qy/department/del';

    /**
     * 获取部门列表的接口地址
     *
     * @var string
     */
    const LIST_URL = '%s/a/%s/%s/department/page-list';

    /**
     * 获取部门列表的接口地址 (无鉴权)
     *
     * @var string
     */
    const IGNORE_PERMISSIONS_LIST_URL = '%s/a/%s/%s/qy/department/ignore-permissions/page-list';

    /**
     * 获取部门详情的接口地址
     *
     * @var string
     */
    const DETAIL_URL = '%s/a/%s/%s/qy/department/detail';

    /**
     * 获取部门类型列表的接口地址
     *
     * @var string
     */
    const TYPE_LIST_URL = '%s/department/type-list';

    /**
     * 获取部门类型配置列表的接口地址
     *
     * @var string
     */
    const FIELD_CONFIG_LIST_URL = '%s/department/field-config-list';

    /**
     * 获取部门列表
     *
     * @param $qyDomain
     * @param $appIdentifier
     * @param array $condition
     * @param bool $ignorePermissions
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/15 3:29 PM
     */
    public function list($qyDomain, $appIdentifier, $condition = [], $ignorePermissions = false)
    {
        $url = vsprintf(self::IGNORE_PERMISSIONS_LIST_URL, [$this->getUcApiUrl(), $qyDomain, $appIdentifier]);

        if ($ignorePermissions) {
            $url = vsprintf(self::LIST_URL, [$this->getUcApiUrl(), $qyDomain, $appIdentifier]);
        }
        return $this->post($url, $condition);
    }

    /**
     * 获取部门详情
     *
     * @param $qyDomain
     * @param $appIdentifier
     * @param $dpId
     * @param array $filterFields
     * @return array|mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/15 3:30 PM
     */
    public function detail($qyDomain, $appIdentifier, $dpId, $filterFields = [])
    {
        $url = vsprintf(self::DETAIL_URL, [$this->getUcApiUrl(), $qyDomain, $appIdentifier]);

        $data = $this->post($url, ['dpId' => $dpId]);
        $returnData = $data;
        // 字段过滤
        if (!empty($filterFields)) {
            $filterFields = array_fill_keys($filterFields, '');
            $returnData = array_intersect_key($returnData, $filterFields);
        }

        return $returnData;
    }

    /**
     * 添加部门
     *
     * @param $qyDomain
     * @param $appIdentifier
     * @param $params
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/15 3:30 PM
     */
    public function add($qyDomain, $appIdentifier, $params)
    {
        $url = vsprintf(self::CREATE_URL, [$this->getUcApiUrl(), $qyDomain, $appIdentifier]);
        return $this->post($url, $params);
    }

    /**
     * 删除部门
     *
     * @param $qyDomain
     * @param $appIdentifier
     * @param $params
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/15 3:30 PM
     */
    public function delete($qyDomain, $appIdentifier, $params)
    {
        $url = vsprintf(self::DELETE_URL, [$this->getUcApiUrl(), $qyDomain, $appIdentifier]);
        return $this->post($url, $params);
    }

    /**
     * 编辑部门
     *
     * @param $qyDomain
     * @param $appIdentifier
     * @param $params
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/15 3:30 PM
     */
    public function edit($qyDomain, $appIdentifier, $params)
    {
        $url = vsprintf(self::MODIFY_URL, [$this->getUcApiUrl(), $qyDomain, $appIdentifier]);
        return $this->post($url, $params);
    }
}
