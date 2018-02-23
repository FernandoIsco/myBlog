<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14
 * Time: 18:09
 */

namespace Emilia\database;


use Emilia\config\Config;
use Emilia\exception\DatabaseException;
use Emilia\log\Logger;

class Query extends BaseQuery
{
    /**
     * @var string 表名称
     */
    protected $table;

    /**
     * @var string 主表名称
     */
    protected $mainTable;

    /**
     * @var string 主表别名
     */
    protected $alias;

    /**
     * @var array 查询字段
     */
    protected $field = array();

    /**
     * @var array 查询条件
     */
    protected $where = array();

    /**
     * @var array 新增或修改数据
     */
    protected $data = array();

    /**
     * @var array 排序
     */
    protected $order = array();

    /**
     * @var int 偏移量
     */
    protected $offset = 0;

    /**
     * @var int 限制条数
     */
    protected $limit = 0;

    /**
     * @var array 连表查询
     */
    protected $join = array();

    /**
     * @var array 分组
     */
    protected $group = array();

    /**
     * @var array 聚合函数
     */
    protected $func = array();

    protected $wherePosition = 0;

    private $operators = array('OR', 'AND');

    /**
     * 获取表名称
     *
     * @author lzl
     *
     * @param string $table 表名称
     *
     * @return string
     */
    public function addPrefix($table)
    {
        $config = Config::getConfig('database');

        return !empty($config['prefix']) ? $config['prefix'] . '_' . $table : $table;
    }

    /**
     * 设置表名称
     *
     * @author lzl
     *
     * @param string|array $table 表名称
     * @param string $type  类型   main: 主表; tmp: 临时表
     *
     * @return $this
     */
    public function setTable($table, $type = 'tmp')
    {
        if (!empty($table)) {

            $table = (array) $table;

            $this->setAlias(current($table));
            $table = is_int(key($table)) ? current($table) : key($table);

            if ($type == 'tmp') {
                $this->table = $this->addPrefix($table);
            } elseif ($type == 'main') {
                $this->mainTable = $this->addPrefix($table);
            }
        }

        return $this;
    }

    /**
     * 设置主表别名
     *
     * @author lzl
     *
     * @param string $alias
     *
     * @return $this
     */
    public function setAlias($alias)
    {
        !empty($alias) && $this->alias = $alias;

        return $this;
    }

    /**
     * 获取表名称
     *
     * @author lzl
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table ? $this->table : $this->mainTable;
    }

    /**
     * 新增操作
     *
     * @author lzl
     *
     * @param array $param 新增数据
     *
     * @return mixed
     */
    public function insert($param = array())
    {
        $res = 0;

        try {
            $sql = $this->data($param)->buildSql(__FUNCTION__);
            $res = $this->prepare($sql)->batchBindParam()->execute();
        } catch (DatabaseException $exception) {
            Logger::record($exception->getMessage());
        }

        $this->flushParam();

        return $res;
    }

    /**
     * 修改操作
     *
     * @author lzl
     *
     * @param array $param 新增数据
     * @param array $where 修改条件
     *
     * @return mixed
     */
    public function update($param = array(), $where = array())
    {
        $res = 0;

        try {
            $sql = $this->data($param)->where($where)->buildSql(__FUNCTION__);
            $res = $this->prepare($sql)->batchBindParam()->execute();
        } catch (DatabaseException $exception) {
            Logger::record($exception->getMessage());
        }

        $this->flushParam();

        return $res;
    }

    /**
     * replace操作
     *
     * @author lzl
     *
     * @param array $param 替换数据
     *
     * @return mixed
     */
    public function replace($param = array())
    {
        $res = 0;

        try {
            $sql = $this->data($param)->buildSql(__FUNCTION__);
            $res = $this->prepare($sql)->batchBindParam()->execute();
        } catch (DatabaseException $exception) {
            Logger::record($exception->getMessage());
        }

        $this->flushParam();

        return $res;
    }

    /**
     * 删除操作
     *
     * @author lzl
     *
     * @param array $where 删除条件
     *
     * @return mixed
     */
    public function delete($where = array())
    {
        $res = 0;

        try {
            $sql = $this->where($where)->buildSql(__FUNCTION__);
            $res = $this->prepare($sql)->batchBindParam()->execute();
        } catch (DatabaseException $exception) {
            Logger::record($exception->getMessage());
        }

        $this->flushParam();

        return $res;
    }

    /**
     * 查询操作
     *
     * @author lzl
     *
     * @param array $where 删除条件
     * @param array $param 查询字段
     *
     * @return mixed
     */
    public function select($where = array(), $param = array())
    {
        $res = array();

        try {
            $sql = $this->field($param)->where($where)->buildSql(__FUNCTION__);
            $res = $this->prepare($sql)->batchBindParam()->fetch();
        } catch (DatabaseException $exception) {
            Logger::record($exception->getMessage());
        }

        $this->flushParam();

        return $res;
    }

    /**
     * 计算总条数
     *
     * @author lzl
     *
     * @param array $where 条件
     * @param string $expr 表达式
     * @param string $alias 别名
     * @param bool $distinct 是否去重
     *
     * @return mixed
     */
    public function count($where = array(), $expr = '*', $alias = __FUNCTION__, $distinct = false)
    {
        $res = $this->where($where)->func(__FUNCTION__, $expr, $alias, $distinct)->select();

        return $res ? reset($res)->{$alias} : 0;
    }

    /**
     * 平均值
     *
     * @author lzl
     *
     * @param array $where 条件
     * @param string $expr 表达式
     * @param string $alias 别名
     * @param bool $distinct 是否去重
     *
     * @return mixed
     */
    public function avg($where = array(), $expr, $alias = __FUNCTION__, $distinct = false)
    {
        $res = $this->where($where)->func(__FUNCTION__, $expr, $alias, $distinct)->select();

        return $res ? reset($res)->{$alias} : 0;
    }

    /**
     * 最大值
     *
     * @author lzl
     *
     * @param array $where 条件
     * @param string $expr 表达式
     * @param string $alias 别名
     * @param bool $distinct 是否去重
     *
     * @return mixed
     */
    public function max($where = array(), $expr = '*', $alias = __FUNCTION__, $distinct = false)
    {
        $res = $this->where($where)->func(__FUNCTION__, $expr, $alias, $distinct)->select();

        return $res ? reset($res)->{$alias} : 0;
    }

    /**
     * 最小值
     *
     * @author lzl
     *
     * @param array $where 条件
     * @param string $expr 表达式
     * @param string $alias 别名
     * @param bool $distinct 是否去重
     *
     * @return mixed
     */
    public function min($where = array(), $expr = '*', $alias = __FUNCTION__, $distinct = false)
    {
        $res = $this->where($where)->func(__FUNCTION__, $expr, $alias, $distinct)->select();

        return $res ? reset($res)->{$alias} : 0;
    }

    /**
     * 计算数值和
     *
     * @author lzl
     *
     * @param array $where 条件
     * @param string $expr 表达式
     * @param string $alias 别名
     * @param bool $distinct 是否去重
     *
     * @return mixed
     */
    public function sum($where = array(), $expr = '*', $alias = __FUNCTION__, $distinct = false)
    {
        $res = $this->where($where)->func(__FUNCTION__, $expr, $alias, $distinct)->select();

        return $res ? reset($res)->{$alias} : 0;
    }

    /**
     * 组装聚合函数sql语句
     *
     * @author lzl
     *
     * @param string $function 聚合函数
     * @param string $expr 表达式
     * @param string $alias 别名
     * @param bool $distinct 是否去重
     *
     * @return mixed
     */
    public function func($function, $expr = 'id', $alias = 'id', $distinct = false)
    {
        $this->func[] = strtoupper($function) . '(' . ($distinct ? 'DISTINCT ' : '') . $expr . ') as ' . $alias;

        return $this;
    }

    /**
     * 处理查询字段
     *
     * @author lzl
     *
     * @param array $field 查询字段
     * @param string $table 表名称
     *
     * @return $this
     */
    public function field($field = array(), $table = '')
    {
        $field = (array)$field;

        $dealField = array();

        !empty($field) && $dealField = array_map(function ($value, $alias) use ($table) {
            if (is_int($value)) $value = $alias;

            $param = $this->getParamWithTable($value, $table);

            return "`{$param['table']}`.`{$param['name']}` as `{$alias}`";
        }, array_keys($field), $field);

        $this->field = array_merge($this->field, $dealField);

        return $this;
    }

    /**
     * 处理新增或修改的数据
     *
     * @author lzl
     *
     * @param array $data 新增或修改的数据
     *
     * @return $this
     */
    public function data($data = array())
    {
        !empty($data) && $this->data = (array)$data;

        return $this;
    }

    /**
     * 处理排序字段
     *
     * @author lzl
     *
     * @param array $order 排序字段
     * @param string $table 表名称
     *
     * @return $this
     */
    public function order($order = array(), $table = '')
    {
        $order = (array)$order;

        $dealOrder = array();

        foreach ($order as $key => $value) {

            $param = $this->getParamWithTable($key, $table);

            $dealOrder["`{$param['table']}`.`{$param['name']}`"] = $value;
        }

        $this->order = array_merge($this->order, $dealOrder);

        return $this;
    }

    /**
     * 处理查询条件
     *
     * @author lzl
     *
     * @param array $where 查询条件
     * @param string $table 表名称
     *
     * @return $this
     */
    public function where($where = array(), $table = '')
    {
        $where = (array)$where;

        $where = $this->parseWhere($where, $table);

        $this->where = array_merge($this->where, $where);

        return $this;
    }

    /**
     * 查询偏移量
     *
     * @author lzl
     *
     * @param int $offset 偏移量
     *
     * @return $this
     */
    public function offset($offset)
    {
        $this->offset = (int)$offset;

        return $this;
    }

    /**
     * 限制条数
     *
     * @author lzl
     *
     * @param int $limit 限制条数
     *
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit = (int)$limit;

        return $this;
    }

    /**
     * 分页查询
     *
     * @author lzl
     *
     * @param int $offset 偏移量
     * @param int $limit 限制条数
     *
     * @return $this
     */
    public function page($offset, $limit)
    {
        $this->offset($offset);
        $this->limit = (int)$limit;

        return $this;
    }

    /**
     * 连表查询
     *
     * @author lzl
     *
     * @param string|array  $joinTable 关联表名称
     * @param string|array  $where     连表条件
     * @param string $joinType  连表方式
     *
     * @return $this
     */
    public function join($joinTable, $where, $joinType = 'LEFT')
    {
        $joinTable = (array) $joinTable;
        $table = $this->addPrefix(is_int(key($joinTable)) ? current($joinTable) : key($joinTable));
        $alias = current($joinTable);

        $where = (array) $where;
        $whereString = '';
        foreach ($where as $value) {
            $whereString .= (empty($whereString) ? ' ' : ' AND ' ) . $value;
        }

        $this->join[] = " {$joinType} JOIN `{$table}` AS `{$alias}` ON {$whereString}";

        return $this;
    }

    /**
     * 构造sql语句
     *
     * @author lzl
     *
     * @param string $operate 操作类型
     *
     * @return string $sql
     */
    protected function buildSql($operate)
    {
        $operate = strtolower($operate);

        $sql = '';

        switch ($operate) {
            case 'select':
                $sql .= 'SELECT ';

                if ($this->func) {
                    $sql .= implode(', ', $this->func);
                } elseif ($this->field) {
                    $sql .= implode(', ', $this->field);
                } else {
                    $sql .= '*';
                }

                $sql .= ' FROM `' . $this->getTable() . '` AS `' . $this->alias . '`' ;

                if ($this->join) {
                    $sql .= implode(' ', $this->join);
                }

                if ($this->where) {
                    $sql .= ' WHERE ' . $this->getWhereString($this->where);
                }

                if ($this->order) {
                    $sql .= ' ORDER BY ' . implode(', ', array_map(function ($name, $orderBy) {
                            return "{$name} {$orderBy}";
                        }, array_keys($this->order), $this->order));
                }

                if ($this->limit) {
                    $sql .= " LIMIT {$this->limit} OFFSET {$this->offset}";
                }

                break;

            case 'update':
                $sql .= 'UPDATE `' . $this->getTable() . '` AS `' . $this->alias . '`';

                if ($this->data) {
                    $sql .= ' SET ' . implode(', ', array_map(function ($value) {
                            return "`{$value}`=:data_{$value}";
                        }, array_keys($this->data)));
                }

                if ($this->where) {
                    $sql .= ' WHERE ' . $this->getWhereString($this->where);
                }

                break;

            case 'insert':
                $sql .= 'INSERT INTO `' . $this->getTable() . '`';

                if ($this->data) {
                    $sql .= ' (' . implode(', ', array_map(function ($value) {
                            return "`{$value}`";
                        }, array_keys($this->data))) . ')';

                    $sql .= ' VALUES (' . implode(',', array_map(function ($value) {
                            return ":data_{$value}";
                        }, array_keys($this->data))) . ')';
                }

                break;

            case 'delete':
                $sql .= 'DELETE ' . ($this->alias ? "`{$this->alias}`" : '') . ' FROM `' . $this->getTable() . '` AS `' . $this->alias . '`';

                if ($this->where) {
                    $sql .= ' WHERE ' . $this->getWhereString($this->where);
                }

                break;

            case 'replace':
                $sql .= 'REPLACE INTO `' . $this->getTable() . '`';

                if ($this->data) {
                    $sql .= ' (' . implode(', ', array_map(function ($value) {
                            return "`{$value}`";
                        }, array_keys($this->data))) . ')';

                    $sql .= ' VALUES (' . implode(',', array_map(function ($value) {
                            return ":data_{$value}";
                        }, array_keys($this->data))) . ')';
                }

                break;
        }

        return $sql;
    }

    /**
     * 处理查询条件
     *
     * @author lzl
     * @param array $where 查询条件
     * @param string $table 表名称
     * @return array
     */
    protected function parseWhere($where, $table)
    {
        $where = (array)$where;

        $dealWhere = array();

        foreach ($where as $key => $value) {
            if (in_array(strtoupper($key), $this->operators)) {
                $dealWhere[strtoupper($key)] = $this->parseWhere($value, $table);
            } else {
                $keys = explode('|', $key);

                $param = $this->getParamWithTable($keys[0], $table);

                $realKey = "{$param['table']}.{$param['name']}.{$this->wherePosition}" . (isset($keys[1]) ? "|{$keys[1]}" : '');

                $dealWhere[$realKey] = $value;

                $this->wherePosition++;
            }
        }

        return $dealWhere;
    }

    /**
     * 获取where语句
     *
     * @author lzl
     *
     * @param array $where where条件
     *
     * @return string
     */
    protected function getWhereString($where)
    {
        if (!$where) return '';

        $string = '';

        foreach ($where as $key => $value) {
            if (is_array($value) && in_array(strtoupper($key), $this->operators)) {
                $string .= strtoupper($key) . ' (' . $this->getWhereString($value) . ') ';
            } else {
                $keys = explode('|', $key);

                $param = explode('.', $keys[0]);

                if (!empty($keys[1])) {
                    $string .= (empty($string) ? "" : "AND ") . $this->whereAlias($keys[1], $param, $value);
                } else {
                    $string .= (empty($string) ? "" : "AND ") . "`{$param[0]}`.`{$param[1]}` = :where_{$param[0]}_{$param[1]}_{$param[2]} ";
                }
            }
        }

        return $string;
    }

    /**
     * where语句运算符
     *
     * @author lzl
     *
     * @param string $opera 运算符
     * @param array $param 参数名
     * @param array $value 参数值
     *
     * @return string
     */
    protected function whereAlias($opera, $param, $value)
    {
        $string = '';

        switch ($opera) {
            case 'between' :
                $string .= "`{$param[0]}`.`{$param[1]}` between :where_{$param[0]}_{$param[1]}_{$param[2]}_0 and :where_{$param[0]}_{$param[1]}_{$param[2]}_1 ";
                break;
            case 'in':
                $value = (array)$value;

                $whereArr = array_map(function ($item) use ($param) {
                    return ":where_{$param[0]}_{$param[1]}_{$param[2]}_{$item}";
                }, array_keys($value));

                $string .= "`{$param[0]}`.`{$param[1]}` in (" . implode(', ', $whereArr) . ") ";
                break;
            case 'like':
                $string .= "`{$param[0]}`.`{$param[1]}` like :where_{$param[0]}_{$param[1]}_{$param[2]} ";
                break;
            case 'gt':
            case '>':
                $string .= "`{$param[0]}`.`{$param[1]}` > :where_{$param[0]}_{$param[1]}_{$param[2]} ";
                break;
            case 'lt':
            case '<':
                $string .= "`{$param[0]}`.`{$param[1]}` < :where_{$param[0]}_{$param[1]}_{$param[2]} ";
                break;
            case 'egt':
            case '>=':
                $string .= "`{$param[0]}`.`{$param[1]}` >= :where_{$param[0]}_{$param[1]}_{$param[2]} ";
                break;
            case 'elt':
            case '<=':
                $string .= "`{$param[0]}`.`{$param[1]}` <= :where_{$param[0]}_{$param[1]}_{$param[2]} ";
                break;
            case 'neq':
            case '!=':
                $string .= "`{$param[0]}`.`{$param[1]}` != :where_{$param[0]}_{$param[1]}_{$param[2]} ";
                break;
        }

        return $string;
    }

    /**
     * PDO绑定参数
     *
     * @author lzl
     *
     * @param string $name 参数名
     * @param string|int $value 参数值
     *
     * @throws DatabaseException
     */
    protected function bindParam($name, $value)
    {
        try {
            $this->pdoStatement->bindParam(":{$name}", $value);
        } catch (\Exception $e) {
            throw new DatabaseException($e->getMessage());
        }

        $this->debugValues["{$name}"][] = $value;
    }

    /**
     * 判断不同情况下的参数绑定
     *
     * @author lzl
     *
     * @param string $name 参数名
     * @param string|int $value 参数值
     * @param string $prefix
     *
     * @throws DatabaseException
     */
    protected function bindParameters($name, $value, $prefix)
    {
        $keys = explode('|', $name);

        $param = $this->getParamWithTable($keys[0]);

        if ($prefix == 'where') {

            if (!empty($keys[1]) && in_array($keys[1], array('between', 'in', 'like'))) {
                switch ($keys[1]) {
                    case 'between' :
                        $value = (array)$value;
                        $value = array_pad($value, 2, 0);

                        foreach (array_keys($value) as $item) {
                            $key = "{$prefix}_{$param['table']}_{$param['name']}_{$this->wherePosition}_{$item}";
                            //$value[$item] = $this->quote($value[$item]);

                            $this->bindParam($key, $value[$item]);
                        }
                        break;
                    case 'in':
                        $value = (array)$value;

                        foreach (array_keys($value) as $item) {
                            $key = "{$prefix}_{$param['table']}_{$param['name']}_{$this->wherePosition}_{$item}";
                            //$value[$item] = $this->quote($value[$item]);

                            $this->bindParam($key, $value[$item]);
                        }
                        break;
                    case 'like':
                        $key = "{$prefix}_{$param['table']}_{$param['name']}_{$this->wherePosition}";
                        //$value = trim($this->quote($value), '%') . '%';
                        $value = trim($value, '%') . '%';

                        $this->bindParam($key, $value);
                        break;
                }
            } else {
                $key = "{$prefix}_{$param['table']}_{$param['name']}_{$this->wherePosition}";
                //$value = $this->quote($value);

                $this->bindParam($key, $value);
            }

            $this->wherePosition++;

        } else {
            $key = "{$prefix}_{$param['name']}";
            //$value = $this->quote($value);

            $this->bindParam($key, $value);
        }
    }

    /**
     * 递归绑定参数
     *
     * @author lzl
     *
     * @param string $name 参数名
     * @param string|int|array $value 参数值
     * @param string $prefix
     */
    protected function recurseBindParam($name, $value, $prefix)
    {
        if (in_array(strtoupper($name), $this->operators)) {
            foreach ($value as $key => $v) {
                $this->recurseBindParam($key, $v, $prefix);
            }
        } else {
            $this->bindParameters($name, $value, $prefix);
        }
    }

    /**
     * 绑定data和where参数
     *
     * @author lzl
     */
    protected function batchBindParam()
    {
        $this->debugValues = array();

        foreach (array('data', 'where') as $item) {
            $this->wherePosition = 0;

            array_map(function ($name, $value) use ($item) {
                $this->recurseBindParam($name, $value, $item);
            }, array_keys($this->{$item}), $this->{$item});
        }

        return $this;
    }

    /**
     * 解析参数名，是否包含表名称
     *
     * @author lzl
     *
     * @param string $name 参数名
     * @param string $table 表名称
     *
     * @return array
     */
    protected function getParamWithTable($name, $table = '')
    {
        $table = empty($table) ? ($this->alias ? $this->alias : $this->getTable()) : $this->addPrefix($table);
        if (strpos($name, '.') !== false) {
            list($table, $name) = explode('.', $name);
        }

        return array('table' => $table, 'name' => $name);
    }

    /**
     * 重置所有变量，避免影响下次查询
     *
     * @author lzl
     */
    protected function flushParam()
    {
        $reflect = new \ReflectionClass($this);

        foreach ($reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED) as $item) {
            if ($item->class != __CLASS__) {
                continue;
            }

            if (is_array($this->{$item->name})) {
                $this->{$item->name} = array();
            } elseif (is_int($this->{$item->name})) {
                $this->{$item->name} = 0;
            } elseif (is_string($this->{$item->name})) {
                $this->{$item->name} = '';
            }
        }
    }
}