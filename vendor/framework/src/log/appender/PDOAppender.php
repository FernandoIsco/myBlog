<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29
 * Time: 10:33
 */

namespace Emilia\log\appender;


use Emilia\database\DbManager;
use Emilia\database\Query;
use Emilia\exception\DatabaseException;
use Emilia\log\appender\base\AppenderBase;
use Emilia\log\appender\base\AppenderInterface;

class PDOAppender extends AppenderBase implements AppenderInterface
{
    protected $db;

    /**
     * 日志记录保存到数据库
     *
     * @author lzl
     *
     * @param string|array|object $content 日志内容
     * @param string $level 日志等级
     *
     * @return bool
     */
    public function write($content, $level)
    {
        if (!$this->checkLevel($level)) {
            return true;
        }

        $res = $this->establishConnect()->exists($this->configure['table']);

        if ($res) {
            $content = $this->format($content);

            try {
                $this->db->setTable($this->configure['table'])->insert($content);
            } catch (DatabaseException $e) {
                $this->warn($e->getMessage());
            }
        }

        return true;
    }

    /**
     * 建立数据库链接
     *
     * @author lzl
     *
     * @return Query
     */
    public function establishConnect()
    {
        try {
            empty($this->db) && $this->db = (new DbManager())->establishQuery();
        } catch (DatabaseException $exception) {
            $this->warn($exception->getMessage());
        }

        return $this->db;
    }

    /**
     * 整理要保存到数据库的内容
     *
     * @author lzl
     *
     * @param string|array|object $content 日志内容
     *
     * @return array
     */
    public function format($content)
    {
        // 数据表字段和系统设置字段的映射关系
        $config = $this->configure['schema'];

        // 获取要写入日志内容
        $message = '';
        if (is_array($content)) {
            $message = isset($content[$config['%message']]) ? $content[$config['%message']] : '';
        } elseif (is_object($content)) {
            if ($content instanceof \Exception) {
                $message = $content;
            } else {
                $key = $config['%message'];
                $message = property_exists($content, $key) ? $content->$key : '';
            }

            $content = json_decode(json_encode($content), true);
        } elseif (is_string($content) || is_int($content)) {
            $message = $content;

            $content = array();
            $content[$config['%message']] = $message;
        } else {
            $this->warn('wrong format message given to write a log');
        }

        $this->layout->debugTrace();
        $this->layout->checkContent($message);

        $return = array();
        $config = array_diff($config, array_keys((array) $content)); // 已定义的数据不自动填充
        foreach ($config as $pattern => $item) {
            $return[$item] = $this->layout->getFormatContent($pattern);
        }

        return array_merge($return, (array) $content);
    }
}