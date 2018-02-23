<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/20
 * Time: 17:53
 */

namespace Emilia\database;


use PDO;
use Emilia\config\Config;
use Emilia\exception\DatabaseException;
use Emilia\log\Logger;

class BaseQuery
{
    /**
     * @var PDO
     */
    protected $connection;

    protected $pdoStatement;

    /**
     * @var array 调试参数
     */
    protected $debugValues = array();

    /**
     * Model constructor.
     * 架构函数
     * @param \PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * 变量转义
     *
     * @author lzl
     *
     * @param string $str
     *
     * @return string
     */
    public function quote($str)
    {
        return $this->connection->quote($str);
    }

    /**
     * 事务开始
     *
     * @author lzl
     */
    public function startTrans()
    {
        $this->connection->startTrans();
    }

    /**
     * 事务提交
     *
     * @author lzl
     */
    public function commit()
    {
        $this->connection->commit();
    }

    /**
     * 事务回滚
     *
     * @author lzl
     */
    public function rollback()
    {
        $this->connection->rollback();
    }

    /**
     * 准备执行sql语句
     *
     * @author lzl
     *
     * @param string $querySql sql语句
     *
     * @return $this
     * @throws DatabaseException
     */
    public function prepare($querySql)
    {
        try {
            $driver = Config::getConfig('dataSource');

            if ($driver == 'postgres') {
                $querySql = str_replace('`', '', $querySql);
            }

            $this->pdoStatement = $this->connection->prepare($querySql);
        } catch (\Exception $e) {
            throw new DatabaseException($e->getMessage());
        }

        return $this;
    }

    /**
     * 检查数据表是否存在
     *
     * @author lzl
     *
     * @param string $table 表名称
     * @param string $database 数据库
     *
     * @return bool
     */
    public function exists($table, $database = '')
    {
        if (empty($table)) return false;

        $dataSource = Config::getConfig('dataSource');

        switch ($dataSource) {
            case 'mysql':
                if (empty($database)) {
                    $config = Config::getConfig($dataSource);
                    $database = $config['database'];
                }
                $sql = "SELECT table_name FROM information_schema.TABLES WHERE table_schema = '{$database}' and table_name = '{$table}'";
                break;
            case 'sqlite':
                $sql = "SELECT tbl_name FROM sqlite_master WHERE type='table' AND name='{$table}'";
                 break;
            case 'postgres':
                $config = Config::getConfig($dataSource);

                $sql = "SELECT * FROM pg_tables WHERE tablename = '{$table}' AND schemaname = '{$config['schema']}' AND tableowner = '{$config['username']}'";
                break;
            default:
                Logger::record('Database driver not supported or is null');
                return false;
        }

        $res =  $this->prepare($sql)->fetch();

        if ($res) return true;

        Logger::record(sprintf('Database table %s is not found', $table));
        return false;
    }

    /**
     * 执行sql语句，返回影响行数
     *
     * @author lzl
     *
     * @return mixed
     * @throws DatabaseException
     */
    public function execute()
    {
        try {
            $this->pdoStatement->execute();

            $this->close();

            return $this->pdoStatement->rowCount();
        } catch (\PDOException $e) {
            throw new DatabaseException($e->getMessage(), $this->debugQuery());
        }
    }

    /**
     * 执行sql语句，返回对象数组列表
     *
     * @author lzl
     *
     * @return mixed
     * @throws DatabaseException
     */
    public function fetch()
    {
        try {
            $this->pdoStatement->execute();

            $list = array();
            while ($obj = $this->pdoStatement->fetch(PDO::FETCH_OBJ)) {
                $list[] = $obj;
            }

            $this->close();

            return $list;
        } catch (\PDOException $e) {
            throw new DatabaseException($e->getMessage(), $this->debugQuery());
        }
    }

    /**
     * 获取最后新增的id
     *
     * @author lzl
     *
     * @return string
     */
    public function getLastId()
    {
        return $this->connection->lastInsertId();
    }

    /**
     * 最后执行sql语句
     *
     * @author lzl
     *
     * @return mixed
     */
    public function debugQuery()
    {
        return preg_replace_callback('/:([0-9a-z_\.]+)/i', array($this, 'getParam'), $this->queryString());
    }

    /**
     * 释放数据连接资源
     *
     * @author lzl
     */
    protected function close()
    {
        $this->pdoStatement->closeCursor();
    }

    /**
     * 获取查询语句
     *
     * @author lzl
     *
     * @return mixed
     */
    protected function queryString()
    {
        return $this->pdoStatement->queryString;
    }

    /**
     * 获取调试参数
     *
     * @author lzl
     *
     * @param string $value 参数名称
     *
     * @return null|string
     */
    protected function getParam($value)
    {
        $value = $this->debugValues[$value[1]];

        if (empty($value)) {
            return null;
        } elseif (is_array($value)) {
            $v = array_shift($value);

            if (!is_numeric($v)) {
                $v = str_replace("'", "''", $v);
            }

            return "'" . $v . "'";
        }

        return null;
    }
}