<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13
 * Time: 11:22
 */

namespace Emilia\cache\driver;


use Emilia\cache\base\BaseStore;
use Emilia\cache\base\Store;

class RedisStore extends BaseStore implements Store
{
    private $redis;

    public function __construct(\Redis $redis, $prefix = '')
    {
        $this->redis = $redis;

        $this->setPrefix($prefix);
    }

    public function get($key)
    {
        $value = $this->redis->get($this->getPrefix() . $key);

        return is_numeric($value) ? $value : unserialize($value);
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

        return $this->redis->getMultiple($keys);
    }

    public function put($key, $value, $expire)
    {
        $value = is_numeric($value) ? $value : serialize($value);

        $this->redis->setex($this->getPrefix() . $key, $expire, $value); // TODO timeout
    }

    /**
     * 批量设置缓存
     *
     * @author lzl
     *
     * @param array $items 一组键值对
     *
     */
    public function multiPut($items)
    {
        $newItems = array();
        $prefix = $this->getPrefix();
        array_map(function ($k, $v) use (&$newItems, $prefix) {
            $newItems[$prefix . $k] = is_numeric($v) ? $v : serialize($v);
        }, array_keys($items), $items);

        $this->redis->mset($items);
    }

    public function add($key, $value)
    {
        return $this->redis->setnx($this->getPrefix() . $key, $value);
    }

    public function forever($key, $value)
    {
        $value = is_numeric($value) ? $value : serialize($value);

        $this->redis->set($this->getPrefix() . $key, $value);
    }

    public function remove($key)
    {
        $this->redis->del($this->getPrefix() . $key);
    }

    public function flush()
    {
        return $this->redis->flushDB();
    }

    public function increment($key, $value = 1)
    {
        return $this->redis->incrBy($key, $value);
    }

    public function decrement($key, $value = 1)
    {
        return $this->redis->decrBy($key, $value);
    }
}