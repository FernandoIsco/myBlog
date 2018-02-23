<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/7
 * Time: 19:54
 */

namespace Emilia\log\appender;


use Emilia\log\appender\base\AppenderBase;
use Emilia\log\appender\base\AppenderInterface;

class FileAppender extends AppenderBase implements AppenderInterface
{
    private $fp = null;

    /**
     * 日志记录写入到文件
     *
     * @author lzl
     *
     * @param string|array|\Exception $content 日志内容
     * @param string $level 日志等级
     *
     * @return bool
     */
    public function write($content, $level)
    {
        if (!$this->checkLevel($level)) {
            return true;
        }

        $filename = isset($this->configure['file']) ? $this->configure['file'] : '/';

        $this->checkFile($filename);

        if (empty($this->fp)) {
            $this->openFile($filename);
        }

        if (flock($this->fp, LOCK_EX)) {
            $content = $this->getContent($content);

            if (fwrite($this->fp, $content) === false) {
                $this->warn("Failed writing to file.");
            }
            flock($this->fp, LOCK_UN);
        } else {
            $this->warn("Failed locking file for writing");
        }

        $this->close();

        return true;
    }

    /**
     * 检查文件是否存在，无则创建
     *
     * @param string $file 日志文件
     *
     * @return bool
     */
    private function checkFile($file)
    {
        if (!is_file($file)) {
            $dir = dirname($file);

            if (!is_dir($dir)) {
                $bool = mkdir($dir, 0777, true);
                if ($bool === false) {
                    $this->warn("Failed creating target log directory [$dir].");
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * 打开文件
     *
     * @param string $file 日志文件
     *
     * @return bool
     */
    private function openFile($file)
    {
        $this->fp = fopen($file, 'ab');
        if ($this->fp === false) {
            $this->warn("Failed opening target file [$file]");
            $this->fp = null;
            return false;
        }

        return true;
    }

    /**
     * 关闭文件资源
     *
     */
    private function close()
    {
        if (is_resource($this->fp)) {
            fclose($this->fp);
        }

        $this->fp = null;
    }
}