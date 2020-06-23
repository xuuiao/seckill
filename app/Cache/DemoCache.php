<?php
/**
 * Created by PhpStorm.
 * User: simple
 * Date: 2018/11/14
 * Time: 10:24 AM
 */

namespace App\Cache;

class DemoCache extends Base
{
    /**
     * 示例数据
     *
     * @var array
     */
    protected static $list = [
        ['name' => '张三'],
        ['name' => '李四'],
        ['name' => '王五'],
        ['name' => '赵六'],
        ['name' => '郝七']
    ];

    /**
     * 定义多条数据缓存
     *
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/14 10:33 AM
     */
    public function list()
    {
        $cache = $this->call(function () {
            return empty(self::$list) ? [] : self::$list;
        });

        return $cache;
    }

    /**
     * 定义单条数据缓存
     *
     * @param $id
     * @return mixed
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/14 3:28 PM
     */
    public function get($id)
    {
        $cache = $this->call(function () use ($id) {
            return empty(self::$list[$id]) ? [] : self::$list[$id];
        });

        return $cache;
    }
}
