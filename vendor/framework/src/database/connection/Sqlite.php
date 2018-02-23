<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/22
 * Time: 14:52
 */

namespace Emilia\database\connection;


use Emilia\config\Config;

class Sqlite
{
    /**
     * @var array 数据库链接配置
     */
    private static $config = array(
        'driver' => 'sqlite',

        'database' => '',

        'prefix' => ''
    );

    /**
     * 组合DSN
     *
     * @author lzl
     *
     * @param array $config 链接配置
     *
     * @return string
     * @throws \Exception
     */
    public static function parseDsn(&$config)
    {
        $setting = Config::getConfig('sqlite');

        $config = array_merge(self::$config, $setting, $config);

        if (isset($config['dsn'])) {
            return $config['dsn'];
        }

        $config['database'] = self::checkDBFile($config['database']);

        if ($config['database'] == ':memory:') {
            return $config['dsn'] = $config['driver'] . ":" . $config['database'];
        } else {

            $database = realpath($config['database']);

            if ($database === false) {
                throw new \PDOException('Sqlite database ' . $config['database'] . ' not found');
            } else {
                return $config['dsn'] = $config['driver'] . ":" . $database;
            }
        }
    }

    /**
     * 检查数据库目录是否存在
     *
     * @author lzl
     *
     * @param string $database
     *
     * @return string
     */
    public static function checkDBFile($database)
    {
        return empty($database) ? ROOT_PATH . 'database/sqlite.sqlite' : $database;
    }
}