<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/22
 * Time: 10:49
 */

namespace Emilia;


class FileSystem
{
    /**
     * 获取文件内容
     *
     * @param string $path 文件
     *
     * @return bool|string
     *
     */
    public function get($path)
    {
        if ($this->isFile($path)) {
            return file_get_contents($path);
        }

        return '';
    }

    /**
     * 递归获取目录下所有文件内容
     *
     * @author lzl
     *
     * @param string $path
     */
    public function getDirectory($path)
    {
        foreach (glob($path . '/*') as $item) {
            if ($this->isDir($item)) {
                $this->getDirectory($item);
            } else {
                $this->get($item);
            }
        }
    }

    /**
     * 文件写入，是否文件锁定
     *
     * @param string $path 文件
     * @param mixed $data 写入内容
     * @param bool $lock 文件锁
     *
     * @return bool|int
     *
     */
    public function write($path, $data, $lock = false)
    {
        return file_put_contents($path, $data, $lock ? LOCK_EX : 0);
    }

    /**
     * 文件写入追加
     *
     * @author lzl
     *
     * @param string $path 文件
     * @param mixed $data 写入内容
     *
     * @return bool|int
     *
     */
    public function append($path, $data)
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }

    /**
     * 文件头部写入
     *
     * @param string $path 文件
     * @param mixed $data 写入内容
     *
     * @return bool|int
     *
     */
    public function prepend($path, $data)
    {
        if ($this->exists($path)) {
            return $this->write($path, $data . $this->get($path));
        }

        return $this->write($path, $data);
    }

    /**
     * 删除文件
     *
     * @param string $path 文件
     *
     * @return bool
     *
     */
    public function unlink($path)
    {
        try {
            return @unlink($path);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 创建路径
     *
     * @param string $path 路径
     * @param int $mode 读写模式
     * @param bool $recursive 是否递归
     *
     * @return bool
     *
     */
    public function makeDirectory($path, $mode = 0755, $recursive = false)
    {
        return @mkdir($path, $mode, $recursive);
    }

    /**
     * 删除路径
     *
     * @param string $path 路径
     *
     * @return bool
     *
     */
    public function removeDirectory($path)
    {
        if (!$this->isDir($path)) {
            return false;
        }

        $files = new \FilesystemIterator($path);

        foreach ($files as $file) {
            if ($file->isDir()) {
                $this->removeDirectory($file->getPathname());
            } elseif ($file->isFile() || $file->isLink()){
                $this->unlink($file->getPathname());
            }
        }

        @rmdir($path);

        return true;
    }

    /**
     * 文件重命名
     *
     * @author lzl
     *
     * @param string $path 原文件
     * @param string $target 目标文件
     * @param int $mode 读写模式
     *
     * @return bool
     */
    public function rename($path, $target, $mode = 0755)
    {
        $res = rename($path, $target);

        if ($res) {
            chmod($target, $mode);
        }

        return $res;
    }

    /**
     * 获取文件路径
     *
     * @param string $path 文件
     *
     * @return mixed
     */
    public function path($path)
    {
        return pathinfo($path, PATHINFO_DIRNAME);
    }

    /**
     * 获取文件名称
     *
     * @param string $path 文件
     *
     * @return mixed
     */
    public function name($path)
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    /**
     * 获取文件后缀
     *
     * @param string $path 文件
     *
     * @return mixed
     */
    public function extension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * 文件是否存在
     *
     * @param string $path 文件
     *
     * @return bool
     */
    public function exists($path)
    {
        return file_exists($path);
    }

    /**
     * 是否是文件
     *
     * @param string $path 文件
     *
     * @return bool
     */
    public function isFile($path)
    {
        return is_file($path);
    }

    /**
     * 是否是路径
     *
     * @param string $path 文件
     *
     * @return bool
     */
    public function isDir($path)
    {
        return is_dir($path);
    }

    /**
     * 最后更新时间
     *
     * @param string $path 文件
     *
     * @return bool|int
     */
    public function lastModified($path)
    {
        return filemtime($path);
    }

    /**
     * 文件是否可写
     *
     * @param string $path 文件
     *
     * @return bool
     */
    public function isWritable($path)
    {
        return is_writable($path);
    }

    /**
     * 文件是否可读
     *
     * @param string $path 文件
     *
     * @return bool
     */
    public function isReadable($path)
    {
        return is_readable($path);
    }

    /**
     * 当前目录是否是 . 或 ..
     *
     * @param string $path
     *
     * @return bool
     */
    public function isDot($path)
    {
        return in_array($path, array('.', '..'));
    }

    /**
     * 引入文件
     *
     * @param string $file
     *
     */
    public function requireOnce($file)
    {
        require_once $file;
    }
}