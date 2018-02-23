<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/23
 * Time: 17:34
 */

namespace Emilia\database\connection;


use Emilia\config\Config;

class Postgres
{
    /**
     * @var array 数据库链接配置
     */
    private static $config = array(
        'driver' => 'pgsql',

        'host' => '127.0.0.1',

        'database' => '',

        'port' => '5432',

        'username' => 'postgres',

        'password' => '',

        'charset' => 'utf8',

        'prefix' => '',

        'schema' => 'public',
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
        $setting = Config::getConfig('postgres');

        $config = array_merge(self::$config, $setting, $config);

        return isset($config['dsn']) ? $config['dsn'] : ($config['dsn'] = $config['driver'] . ":host=" . $config['host'] . ";port=" . $config['port'] . ';dbname=' . $config['database']);
    }
}