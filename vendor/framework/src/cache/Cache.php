<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/9
 * Time: 15:00
 */

namespace Emilia\cache;


use Emilia\cache\driver\FileStore;
use Emilia\cache\driver\MemcachedStore;
use Emilia\cache\driver\MemcacheStore;
use Emilia\cache\driver\RedisStore;
use Emilia\config\Config;
use Emilia\FileSystem;

class Cache
{
    /**
     * @var array 缓存驱动
     */
    public static $store = array();

    /**
     * 创建文件缓存驱动
     *
     * @author lzl
     *
     * @param string $path 设置缓存路径
     *
     * @return mixed
     */
    public static function createFileStore($path = '')
    {
        empty(self::$store['file']) && self::$store['file'] = new FileStore(new FileSystem(), $path);

        return self::$store['file'];
    }

    /**
     * 创建memcached缓存驱动
     *
     * @author lzl
     *
     * @param string $prefix 设置缓存键前缀
     *
     * @return mixed
     */
    public static function createMemcachedStore($prefix = '')
    {
        if (empty(self::$store['memcached'])) {
            $memcached = new \Memcached();

            $config = Config::getConfig('memcached');

            $memcached->addServers($config); // TODO connect params

            self::$store['memcached'] = new MemcachedStore($memcached, $prefix);
        }

        return self::$store['memcached'];
    }

    /**
     * 创建memcache缓存驱动
     *
     * @author lzl
     *
     * @param string $prefix 设置缓存键前缀
     *
     * @return mixed
     */
    public static function createMemcacheStore($prefix = '')
    {
        if (empty(self::$store['memcache'])) {
            $memcache = new \Memcache();

            $config = Config::getConfig('memcache');

            foreach ($config as $item) {
                $memcache->addServer($item['host'], $item['port'], true, $item['weight']); // TODO connect params
            }

            self::$store['memcache'] = new MemcacheStore($memcache, $prefix);
        }

        return self::$store['memcache'];
    }

    /**
     * 创建redis缓存驱动
     *
     * @author lzl
     *
     * @param string $prefix 设置缓存键前缀
     *
     * @return mixed
     */
    public static function createRedisStore($prefix = '')
    {
        if (empty(self::$store['redis'])) {
            $redis = new \Redis();

            $config = Config::getConfig('redis');

            $redis->connect($config['host'], $config['port'], $config['timeout']);

            self::$store['redis'] = new RedisStore($redis, $prefix);
        }

        return self::$store['redis'];
    }
}