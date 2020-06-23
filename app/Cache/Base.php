<?php
/**
 * Created by PhpStorm.
 * User: simple
 * Date: 2018/11/14
 * Time: 10:23 AM
 */

namespace App\Cache;

use App\Exceptions\Error;
use Redis;

abstract class Base
{
    /**
     * 缓存默认过期时间
     * @var int
     */
    protected $ttl = 7200;

    /**
     * 更新缓存的标志
     *
     * @var bool
     */
    private $update = false;

    /**
     * 删除缓存的标志
     *
     * @var bool
     */
    private $delete = false;

    /**
     * 调用缓存方法
     *
     * tip：该方法只能集成禁止重写
     *
     * @param callable $callback
     * @return bool
     */
    final protected function call(callable $callback)
    {
        $cacheKey = $this->key();
        $cache = $this->get($cacheKey);

        // 删除缓存
        if ($this->delete) {
            $this->delete = false;
            return empty($cache) ? true : $this->del($cacheKey);
        }

        // 没有读取到缓存或者需要强制更新缓存，则调用定义好的缓存方法
        if (empty($cache) || $this->update) {
            $this->update = false;
            $data = $callback();
            $value = serialize($data);
            if ($this->set($cacheKey, $value)) {
                return $data;
            }
            return false;
        }

        return unserialize($cache);
    }

    /**
     * 设置需要更新缓存
     *
     * tip：该方法只能集成禁止重写
     *
     * @return $this
     */
    final public function update()
    {
        $this->update = true;
        return $this;
    }

    /**
     * 设置需要删除缓存
     *
     * tip：该方法只能集成禁止重写
     *
     * @return $this
     */
    final public function delete()
    {
        $this->delete = true;
        return $this;
    }

    /**
     * 根据缓存前缀批量删除指定缓存
     *
     * @param string $key 指定要删除的缓存key值
     *
     * @return bool
     */
    final public function remove($key)
    {
        $keys = $this->redis()->keys($key . '*');

        if (empty($keys) || !is_array($keys)) {
            return true;
        }

        foreach ($keys as $key) {
            $this->del($key);
        }

        return true;
    }

    /**
     * 缓存key
     *
     * 保证key唯一 crs:className:functionName:md5(params)
     *
     * @return string
     */
    private function key()
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 3);
        $trace = array_pop($trace);
        $class = $trace['class'];
        $args = $trace['args'];
        $function = $trace['function'];

        return  'crs:'. strtolower(str_replace('\\', ':', $class)) . ':' . $function . ':' . md5(serialize($args));
    }

    /**
     * 设置缓存
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    private function set($key, $value)
    {
        return $this->redis()->set($key, $value, $this->ttl);
    }

    /**
     * 读取缓存
     *
     * @param $key
     * @return mixed
     */
    private function get($key)
    {
        return $this->redis()->get($key);
    }

    /**
     * 删除缓存
     *
     * @param $key
     * @return mixed
     */
    private function del($key)
    {
        return $this->redis()->del($key);
    }

    /**
     * @param string $connection
     * @return \Redis | mixed
     * @author   陈朔  chenshuo@vchangyi.com
     * @date     2018/11/14 11:17 AM
     */
    private function redis($connection = 'default')
    {
        $config = config('database.redis.'.$connection);

        if (!is_array($config)) {
            throw new Error(1000, 'Redis Config Error');
        }

        $redis = new Redis();

        $redis->connect($config['host'], $config['port']);

        if (!empty($config['password'])) {
            $redis->auth($config['password']);
        }

        return $redis;
    }
}

