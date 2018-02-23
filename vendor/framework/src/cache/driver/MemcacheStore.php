<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/12
 * Time: 16:38
 */

namespace Emilia\cache\driver;


use Emilia\cache\base\BaseStore;
use Emilia\cache\base\Store;

class MemcacheStore extends BaseStore implements Store
{
    /**
     * @var \Memcached memcache实例
     */
    private $memcache;

    /**
     * @var int linux文件权限算法。1表示经过序列化，但未经过压缩，2表明压缩而未序列化，3表明压缩并且序列化，0表明未经过压缩和序列化
     */
    private $flag = 0;

    /**
     * MemcacheStore constructor.
     *
     * @param \Memcache $memcache memcache实例
     * @param string $prefix 键前缀
     */
    public function __construct(\Memcache $memcache, $prefix = '')
    {
        $this->memcache = $memcache;

        $this->setPrefix($prefix);
    }

    public function get($key)
    {
        return $this->memcache->get($this->getPrefix() . $key, $this->flag);
    }

    public function put($key, $value, $expire)
    {
        $this->memcache->set($this->getPrefix() . $key, $value, $this->flag, $this->expiration($expire));
    }

    /**
     * 储存一个元素，当该key不存在
     *
     * @author lzl
     *
     * @param string $key  键
     * @param mixed $value 值
     * @param int $expire  逾期时间。单位：分
     *
     * @return bool
     */
    public function add($key, $value, $expire)
    {
        return $this->memcache->add($this->getPrefix() . $key, $value, $this->flag, $this->expiration($expire));
    }

    public function forever($key, $value)
    {
        $this->memcache->add($this->getPrefix() . $key, $value, $this->flag, 0);
    }

    public function remove($key)
    {
        return $this->memcache->delete($this->getPrefix() . $key);
    }

    public function flush()
    {
        return $this->memcache->flush();
    }

    public function increment($key, $value = 1)
    {
        return $this->memcache->increment($this->getPrefix() . $key, $value);
    }

    public function decrement($key, $value = 1)
    {
        return $this->memcache->decrement($this->getPrefix() . $key, $value);
    }
}