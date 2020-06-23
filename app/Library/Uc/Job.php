<?php
/**
 * Created by PhpStorm.
 * User: simple
 * Date: 2018/10/14
 * Time: 10:14 AM
 */

namespace App\Library\Uc;

class Job extends Abstracts
{
    /**
     * 添加职位接口地址
     *
     * @var string
     */
    const ADD_URL = '%s/b/%s/qy/job/add';

    /**
     * 编辑职位接口地址
     *
     * @var string
     */
    const EDIT_URL = '%s/b/%s/qy/job/update';

    /**
     * 职位列表接口地址
     *
     * @var string
     */
    const LIST_URL = '%s/b/%s/qy/job/page-list';

    /**
     * 删除职位接口地址
     *
     * @var string
     *
     */
    const DELETE_URL = '%s/b/%s/qy/job/delete';

    /**
     * 职位详情接口地址
     *
     * @var string
     */
    const DETAIL_URL = '%s/b/%s/qy/job/get';

    /**
     * 添加职位
     *
     * @param $qyDomain
     * @param array $condition
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/10/14 10:52 AM
     */
    public function add($qyDomain, $condition = [])
    {
        $url = vsprintf(self::ADD_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $condition);
    }

    /**
     * 编辑职位
     *
     * @param $qyDomain
     * @param array $condition
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/10/14 10:52 AM
     */
    public function edit($qyDomain, $condition = [])
    {
        $url = vsprintf(self::EDIT_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $condition);
    }

    /**
     * 删除职位
     *
     * @param $qyDomain
     * @param array $condition
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/10/14 10:52 AM
     */
    public function delete($qyDomain, $condition = [])
    {
        $url = vsprintf(self::DELETE_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $condition);
    }

    /**
     * 职位列表
     *
     * @param $qyDomain
     * @param array $condition
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/10/14 10:52 AM
     */
    public function list($qyDomain, $condition = [])
    {
        $url = vsprintf(self::LIST_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $condition);
    }

    /**
     * 职位详情
     *
     * @param $qyDomain
     * @param array $condition
     * @return mixed
     * @throws \Exception
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/10/14 10:53 AM
     */
    public function detail($qyDomain, $condition = [])
    {
        $url = vsprintf(self::DETAIL_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $condition);
    }
}
