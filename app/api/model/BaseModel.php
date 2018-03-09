<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/24
 * Time: 17:57
 */

namespace app\api\model;


use Emilia\http\Request;
use Emilia\mvc\Model;

class BaseModel extends Model
{
    const SEARCH_LIMIT = 10;

    const SEARCH_PAGE = 1;

    protected $request;

    protected $query;

    public function __construct(Request $request, $query = null)
    {
        $this->request = $request;
        $this->query = $query;
        parent::__construct();
    }

    /**
     * 新增记录
     *
     * @param array $data
     * @return bool
     */
    final public function add($data)
    {
        if (empty($data)) {
            return false;
        }

        $data = array_merge($data, array('create_at' => time()));

        $this->insert($data);

        return $this->getLastId();
    }

    /**
     * 编辑记录
     *
     * @param array $data
     * @param array $where
     * @return bool
     */
    final public function edit($data, $where)
    {
        if (empty($where)) {
            return false;
        }

        $data = array_merge($data, array('last_modify' => time()));

        return $this->update($data, $where);
    }

    /**
     * 删除记录
     *
     * @param array $where
     * @return bool
     */
    final public function remove($where)
    {
        if (empty($where)) {
            return false;
        }

        return $this->delete($where);
    }

    /**
     * 获取一条记录
     *
     * @param array $where
     * @param array $field
     * @param array $order
     * @param array $join
     * @return array
     */
    final public function getRow($where, $field = array(), $order = array(), $join = array())
    {
        $this->joinTable($join);

        $list = $this->getList($where, $field, $order, 1);

        return !empty($list) ? $list[0] : array();
    }

    /**
     * 获取多条记录
     *
     * @param array $where
     * @param array $field
     * @param int   $limit
     * @param int   $page
     * @param array $order
     * @param array $join
     * @return mixed
     */
    final public function getList($where = array(), $field = array(), $order = array(), $limit = 0, $page = self::SEARCH_PAGE, $join = array())
    {
        $start = bcmul($this->getPageNum($page) - 1, $this->getLimit($limit));

        $this->joinTable($join);

        return $this->order($order)->page($start, $limit)->select($where, $field);
    }

    /**
     * 获取列表数量
     *
     * @author lzl
     * @param array $where
     * @param array $join
     * @return mixed
     */
    final public function getTotal($where, $join = array())
    {
        $this->joinTable($join);

        return $this->where($where)->count();
    }

    /**
     * 分页查询
     *
     * @param array $where
     * @param array $field
     * @param int   $limit
     * @param int   $page
     * @param array $order
     * @param array $join
     * @return array
     */
    final public function getPage($where = array(), $field = array(), $order = array(), $limit = self::SEARCH_LIMIT, $page = self::SEARCH_PAGE, $join = array())
    {
        $list = $this->getList($where, $field, $order, $limit, $page, $join);

        $total = $this->getTotal($where, $join);

        return array('total' => $total, 'page' => $page, 'rows' => count($list), 'list' => $list);
    }

    /**
     * 接口使用，自动填充请求字段和条件的单条查询
     *
     * @param array $where
     * @param array $field
     * @param array $order
     * @return array
     */
    final public function getApiRow($where = array(), $field = array(), $order = array(), $join = array())
    {
        return $this->getRow(
            $this->getWhere($where),
            $this->getField($field),
            $this->getOrder($order),
            $this->getJoin($join)
        );
    }

    /**
     * 接口使用，自动填充请求字段和条件的列表查询
     *
     * @param array $where
     * @param array $field
     * @param int   $limit
     * @param int   $page
     * @param array $order
     * @param array $join
     * @return mixed
     */
    final public function getApiList($where = array(), $field = array(), $order = array(), $limit = 0, $page = self::SEARCH_PAGE, $join = array())
    {
        return $this->getList(
            $this->getWhere($where),
            $this->getField($field),
            $this->getOrder($order),
            $this->getLimit($limit),
            $this->getPageNum($page),
            $this->getJoin($join)
        );
    }

    /**
     * 接口使用，自动填充请求条件的条数查询
     *
     * @param array $where
     * @param array $join
     * @return mixed
     */
    final public function getApiTotal($where, $join = array())
    {
        return $this->getTotal($this->getWhere($where), $this->getJoin($join));
    }

    /**
     * 接口使用，自动填充请求字段和条件的列表分页查询
     *
     * @param array $where
     * @param array $field
     * @param int   $limit
     * @param int   $page
     * @param array $order
     * @param array $join
     * @return mixed
     */
    final public function getApiPage($where = array(), $field = array(), $order = array(), $limit = self::SEARCH_LIMIT, $page = self::SEARCH_PAGE, $join = array())
    {
        return $this->getPage(
            $this->getWhere($where),
            $this->getField($field),
            $this->getOrder($order),
            $this->getLimit($limit),
            $this->getPageNum($page),
            $this->getJoin($join)
        );
    }

    /**
     * 获取查询条件参数
     *
     * @param array $where
     * @return array
     */
    public function getWhere($where = array())
    {
        $w = array();
        if (!empty($query = $this->query->where)) {
            $joinKey = $query->getOption('joinKey');

            foreach ($query as $key => $value) {
                $key = isset($joinKey[$key]) ? $joinKey[$key] : $key;

                if (is_object($value) || is_array($value)) {
                    $value = (array)$value;
                    $opera = array_shift($value);
                    $w[$key . '|' . $opera] = count($value) == 1 ? reset($value) : array_values($value);
                } else {
                    ($value !== '' && $value !== NULL) && $w[$key] = $value;
                }
            }

            $w = array_merge($w, $where);
        }

        return $w;
    }

    /**
     * 获取排序条件
     *
     * @param array $order
     * @return array
     */
    public function getOrder($order = array())
    {
        if (isset($this->query->table->order)) {
            $or = json_decode(json_encode($this->query->table->order), true);
            return array_merge($or, $order);
        }

        return $order;
    }

    /**
     * 获取限制条件
     *
     * @param int $limit
     * @return int
     */
    public function getLimit($limit = self::SEARCH_LIMIT)
    {
        return isset($this->query->table->limit) ? $this->query->table->limit : $limit;
    }

    /**
     * 获取页数
     *
     * @param int $page
     * @return int
     */
    public function getPageNum($page = self::SEARCH_PAGE)
    {
        return isset($this->query->table->page) ? $this->query->table->page : $page;
    }

    /**
     * 获取配置填写的字段
     *
     * @param array $field
     * @return array
     */
    public function getField($field)
    {
        $define = $this->query->field();

        return array_merge($define, $field);
    }

    /**
     * 获取配置填写的连表查询条件
     *
     * @param array $join
     * @return array
     */
    public function getJoin($join)
    {
        $define = $this->query->join();

        $join = array_merge($define, $join);

        return array(
            'table' => isset($join['table']) ? $join['table'] : '',
            'condition' => isset($join['condition']) ? $join['condition'] : '',
            'type' => isset($join['type']) ? $join['type'] : 'left',
        );
    }

    /**
     * 连表查询
     *
     * @param array $join
     * @return $this
     */
    public function joinTable($join)
    {
        if (!empty($join['table'])) {
            $this->join($join['table'], $join['condition'], $join['type']);
        }

        return $this;
    }
}