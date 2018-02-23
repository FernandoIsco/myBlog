<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/11
 * Time: 9:32
 */

namespace Emilia\cache\driver;


use Emilia\cache\base\BaseStore;
use Emilia\cache\base\Store;

class MemcachedStore extends BaseStore implements Store
{
    /**
     * @var \Memcached memcached实例
     */
    private $memcached;

    /**
     * MemcachedStore constructor.
     *
     * @param \Memcached $memcached memcached实例
     * @param string $prefix 键前缀
     */
    public function __construct(\Memcached $memcached, $prefix = '')
    {
        $this->memcached = $memcached;

        $this->setPrefix($prefix);
    }

    public function get($key)
    {
        $value = $this->memcached->get($this->getPrefix() . $key);

        return $this->memcached->getResultCode() == 0 ? $value : '';
    }

    /**
     * 批量获取缓存
     *
     * @author lzl
     *
     * @param array $keys 键数组
     *
     * @return mixed
     */
    public function multiGet($keys)
    {
        $prefix = $this->getPrefix();
        $keys = array_map(function ($k) use ($prefix) {
            return $prefix . $k;
        }, $keys);

        return $this->memcached->getMulti($keys);
    }

    public function put($key, $value, $expire)
    {
        $this->memcached->set($this->getPrefix() . $key, $value, $this->expiration($expire));
    }

    public function forever($key, $value)
    {
        $this->memcached->set($this->getPrefix() . $key, $value, 0);
    }

    /**
     * 批量设置缓存
     *
     * @author lzl
     *
     * @param array $items 一组键值对
     * @param int $expire  逾期时间。单位：分
     *
     */
    public function multiPut($items, $expire)
    {
        $newItems = array();
        $prefix = $this->getPrefix();
        array_map(function ($k, $v) use (&$newItems, $prefix) {
            $newItems[$prefix . $k] = $v;
        }, array_keys($items), $items);

        $this->memcached->setMulti($newItems, $expire);
    }

    /**
     * 储存一个元素，当该key不存在
     *
     * @author lzl
     *
     * @param string $key 键
     * @param mixed $value 值
     * @param int $expire 逾期时间。单位：分
     *
     * @return bool
     */
    public function add($key, $value, $expire)
    {
        return $this->memcached->add($this->getPrefix() . $key, $value, $this->expiration($expire));
    }

    public function remove($key)
    {
        return $this->memcached->delete($this->getPrefix() . $key);
    }

    public function increment($key, $value = 1)
    {
        return $this->memcached->increment($this->getPrefix() . $key, $value);
    }

    public function decrement($key, $value = 1)
    {
        return $this->memcached->decrement($this->getPrefix() . $key, $value);
    }

    public function flush()
    {
        return $this->memcached->flush();
    }
}