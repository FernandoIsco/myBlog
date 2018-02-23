<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18
 * Time: 20:12
 */

namespace Emilia\database;

use PDO;
use Emilia\config\Config;
use Emilia\exception\DatabaseException;

class Connection
{
    /**
     * @var array 数据库链接池
     */
    private static $pool = [];

    /**
     * 创建数据库链接
     *
     * @author lzl
     *
     * @param array $config 数据库链接自定义配置
     *
     * @return mixed
     * @throws DatabaseException
     */
    public function createConnection($config = array())
    {
        if (!empty($config['driver'])) {
            $dataSource = $config['driver'];
        } elseif (!empty($config['dsn'])) {
            $dataSource = substr($config['dsn'], 0, strpos($config['dsn'], ':')); // TODO 等我了解其他数据库
        } else {
            $dataSource = Config::getConfig('dataSource');
        }

        $class = 'Emilia\\database\\connection\\' . ucfirst($dataSource);

        try {
            $dsn = $class::parseDsn($config);

            if (empty(self::$pool[$dsn])) {
                $username = isset($config['username']) ? $config['username'] : '';
                $password = isset($config['password']) ? $config['password'] : '';
                $options = isset($config['options']) ? $config['options'] : array();

                self::$pool[$dsn] = new PDO($config['dsn'], $username, $password, $options);
            }
        } catch (\PDOException $exception) {
            throw new DatabaseException($exception->getMessage(), '', $config);
        }

        return self::$pool[$dsn];
    }

}