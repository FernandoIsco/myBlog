<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/6/29
 * Time: 21:48
 */

namespace Emilia\mvc;


use Emilia\database\DbManager;

abstract class Model
{
    /**
     * @var string 主表名称
     */
    protected $table;

    /**
     * @var string 主表别名
     */
    protected $alias;

    /**
     * @var \Emilia\database\Query 数据查询Query对象
     */
    private static $db;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        if (empty(self::$db)) {
            self::$db = (new DbManager())->establishQuery();
        }

        if (empty($this->table)) {
            $table = explode('\\', get_called_class());
            $this->table = strtolower(end($table));
        }

        return self::$db;
    }

    /**
     * 魔术方法，调用Query对象的方法
     *
     * @author lzl
     *
     * @param string $name 方法名称
     * @param array|string $arguments 参数
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        self::$db->setTable($this->table, 'main');

        !empty($this->alias) && self::$db->setAlias($this->alias); // TODO 这里有bug，先调用setTable再调用setAlias会有问题

        return call_user_func_array(array(self::$db, $name), $arguments);
    }
}