<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/19
 * Time: 10:03
 */

namespace Emilia\database;

use PDO;
use Emilia\exception\DatabaseException;
use Emilia\log\Logger;

class DbManager
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var Query 数据查询Query对象
     */
    private $query;

    /**
     * 建立数据库链接
     *
     * @author lzl
     *
     * @param array $config 链接配置
     *
     * @return mixed|PDO
     */
    public function establishConnect($config = array())
    {
        try {
            if (empty($this->pdo)) {
                $this->pdo = (new Connection())->createConnection($config);
            }
        } catch (DatabaseException $exception) {
            Logger::record($exception->getMessage());
        }

        return $this->pdo;
    }

    /**
     * 创建Query对象
     *
     * @author lzl
     *
     * @param array $config 链接配置
     *
     * @return Query
     */
    public function establishQuery($config = array())
    {
        $this->query = new Query($this->establishConnect($config));

        return $this->query;
    }

    /**
     * DQL
     *
     * @author lzl
     *
     * @param string $sql 查询sql语句
     *
     * @return array|mixed
     */
    public function query($sql)
    {
        $res = array();
        try {
            $res = $this->query->prepare($sql)->fetch();
        } catch (DatabaseException $exception) {
            Logger::record($exception->getMessage());
        }

        return $res;
    }

    /**
     * DML
     *
     * @author lzl
     *
     * @param string $sql 执行sql语句
     *
     * @return mixed|string
     */
    public function execute($sql)
    {
        $res = '';
        try {
            $res = $this->query->prepare($sql)->execute();
        } catch (DatabaseException $exception) {
            Logger::record($exception->getMessage());
        }

        return $res;
    }
}