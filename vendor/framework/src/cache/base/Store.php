<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/9
 * Time: 13:57
 */

namespace Emilia\cache\base;

interface Store
{
    /**
     * 通过key获取缓存
     *
     * @author lzl
     *
     * @param string $key 缓存键名
     *
     * @return mixed
     */
    public function get($key);

    /**
     * 设置缓存
     *
     * @author lzl
     *
     * @param string $key    缓存键名
     * @param string $value  缓存值
     * @param int    $expire 逾期时间，单位：分
     *
     * @return mixed
     */
    public function put($key, $value, $expire);

    /**
     * 设置永久缓存
     *
     * @author lzl
     *
     * @param string $key    缓存键名
     * @param string $value  缓存值
     *
     * @return mixed
     */
    public function forever($key, $value);

    /**
     * 移除某个缓存值
     *
     * @author lzl
     *
     * @param string $key 缓存键名
     *
     * @return mixed
     */
    public function remove($key);

    /**
     * 缓存某个值加一
     *
     * @author lzl
     *
     * @param string $key 缓存键名
     * @param int $value  缓存值
     *
     * @return mixed
     */
    public function increment($key, $value = 1);

    /**
     * 缓存某个值减一
     *
     * @author lzl
     *
     * @param string $key 缓存键名
     * @param int $value  缓存值
     *
     * @return mixed
     */
    public function decrement($key, $value = 1);

    /**
     * 移除缓存路径下的所有缓存文件
     *
     * @author lzl
     *
     * @return mixed
     */
    public function flush();
}