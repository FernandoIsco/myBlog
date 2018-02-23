<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/2
 * Time: 17:37
 */

namespace Emilia\session\handlers;


use Emilia\config\Config;
use Emilia\database\DbManager;
use Emilia\exception\DatabaseException;
use Emilia\log\Logger;

class PDOSessionHandler implements \SessionHandlerInterface
{
    /**
     * @var null 数据库实例
     */
    public $db = null;

    /**
     * @var array PDOSession 配置
     */
    public $configure = array();

    /**
     * @var array 保存到数据库的数据
     */
    public static $data = array();

    public function open($save_path, $name)
    {
        $this->configure = Config::getConfig('session');

        try {
            empty($this->db) && $this->db = (new DbManager())->establishQuery();
        } catch (DatabaseException $exception) {
            Logger::record($exception->getMessage());
        }

        return true;
    }

    public function read($session_id)
    {
        $schema = $this->configure['schema'];

        $res = $this->db->setTable($this->configure['table'])->select(array($schema['session_id'] => $session_id, $schema['last_modify'] . "|egt" => (time() - $this->configure['expire'])));

        return ($res && $res = reset($res)) ? $res->session_data : '';
    }

    public function write($session_id, $session_data)
    {
        $res = $this->db->exists($this->configure['table']);

        if ($res) {
            $schema = $this->configure['schema'];

            foreach ($schema as $key => $item) {
                switch ($key) {
                    case 'session_id':
                        self::$data[$item] = $session_id;
                        break;
                    case 'session_data':
                        self::$data[$item] = $session_data;
                        break;
                    case 'last_modify':
                        self::$data[$item] = time();
                        break;
                }
            }

            $this->db->setTable($this->configure['table'])->replace(self::$data);
        }

        return $this->initData();
    }

    public function close()
    {
        return $this->initData();
    }

    public function destroy($session_id)
    {
        $schema = $this->configure['schema'];

        $this->db->setTable($this->configure['table'])->delete(array($schema['session_id'] => $session_id));

        return true;
    }

    public function gc($maxlifetime)
    {
        $schema = $this->configure['schema'];

        $this->db->setTable($this->configure['table'])->delete(array($schema['last_modify'] . "|lt" => (time() - $maxlifetime)));

        return true;
    }

    /**
     * 设置额外保存到数据库的数据
     *
     * @author lzl
     *
     * @param array $data 额外字段和值
     *
     * @return bool
     */
    public static function setData($data = array())
    {
        if (empty($data)) {
            return false;
        }

        self::$data = $data;

        return true;
    }

    /**
     * 初始化数据
     *
     * @author lzl
     *
     * @return bool
     */
    public function initData()
    {
        self::$data = array();

        return true;
    }
}