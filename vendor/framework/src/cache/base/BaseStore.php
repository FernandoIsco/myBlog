<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/11
 * Time: 9:40
 */

namespace Emilia\cache\base;


class BaseStore
{
    protected $prefix = '';

    /**
     * 设置缓存前缀
     *
     * @author lzl
     *
     * @param string $prefix
     */
    public function setPrefix($prefix = '')
    {
        $this->prefix = $prefix;
    }

    /**
     * 获取缓存前缀
     *
     * @author lzl
     *
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->prefix ? $this->prefix . '_' : '';
    }

    /**
     * 计算逾期时间
     *
     * @author lzl
     *
     * @param int $expire 逾期时间，单位：分
     *
     * @return int
     */
    public function expiration($expire)
    {
        return time() + $expire * 60;
    }
}