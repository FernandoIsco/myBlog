<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/22
 * Time: 14:45
 */

namespace Emilia\database\connection;

use PDO;
use Emilia\config\Config;

class Mysql
{
    /**
     * @var array 数据库链接配置
     */
    private static $config = array(
        'driver' => 'mysql',

        'host' => '127.0.0.1',

        'database' => '',

        'port' => '',

        'username' => '',

        'password' => '',

        'charset' => 'utf-8',

        'collation' => '',

        'prefix' => '',

        'engine' => 'innoDB',

        // TODO
        'options' => array(
            PDO::ATTR_CASE => PDO::CASE_NATURAL,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
            PDO::ATTR_STRINGIFY_FETCHES => false,
        ),
    );

    /**
     * 组合DSN
     *
     * @author lzl
     *
     * @param array $config 链接配置
     *
     * @return string
     */
    public static function parseDsn(&$config)
    {
        $setting = Config::getConfig('mysql');

        $config = array_merge(self::$config, $setting, $config);

        return isset($config['dsn']) ? $config['dsn'] : ($config['dsn'] = $config['driver'] . ":host=" . $config['host'] . ";port=" . $config['port'] . ';dbname=' . $config['database']);
    }
}