<?php
/**
 * Created by PhpStorm.
 * User: simple
 * Date: 2018/10/12
 * Time: 10:33 AM
 */

namespace App\Library\Uc;

class EnterprisePlugin extends Abstracts
{
    /**
     * 应用列表接口
     *
     * @var string
     */
    const LIST_URL = '%s/b/%s/enplugin/list';

    /**
     * 应用详情接口
     *
     * @var string
     */
    const DETAIL_URL = '%s/b/%s/enplugin/detail';

    /**
     * 应用安装接口
     *
     * @var string
     */
    const INSTALL_URL = '%s/b/%s/enplugin/install';

    /**
     * 应用卸载接口
     *
     * @var string
     */
    const UNINSTALL_URL = '%s/b/%s/enplugin/uninstall';

    /**
     * 获取应用列表接口
     *
     * @param string $qyDomain 企业域名
     * @param array $params 筛选条件
     * @return array|\Psr\Http\Message\StreamInterface
     * @throws \Exception
     */
    public function list($qyDomain, $params = [])
    {
        $url = vsprintf(self::LIST_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $params);
    }

    /**
     * 获取应用详情接口
     *
     * @param string $qyDomain 企业域名
     * @param array $params 筛选条件
     * @return array|\Psr\Http\Message\StreamInterface
     * @throws \Exception
     */
    public function detail($qyDomain, $params = [])
    {
        $url = vsprintf(self::DETAIL_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $params);
    }

    /**
     * 安装应用接口
     *
     * @param string $qyDomain 企业域名
     * @param array 企业应用信息
     * @return array|\Psr\Http\Message\StreamInterface
     * @throws \Exception
     */
    public function install($qyDomain, $plugin)
    {
        $url = vsprintf(self::INSTALL_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $plugin);
    }

    /**
     * 卸载应用接口
     *
     * @param string $qyDomain 企业域名
     * @param array 企业应用信息
     * @return array|\Psr\Http\Message\StreamInterface
     * @throws \Exception
     */
    public function uninstall($qyDomain, $plugin)
    {
        $url = vsprintf(self::UNINSTALL_URL, [$this->getUcApiUrl(), $qyDomain]);
        return $this->post($url, $plugin);
    }
}