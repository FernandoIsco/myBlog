<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/8
 * Time: 15:58
 */

namespace Emilia;


use Emilia\exception\FileException;

class UploadFile extends \SplFileInfo
{
    /**
     * @var FileSystem 文件系统实例
     */
    public $file;

    /**
     * @var int 文件上传错误编号
     */
    public $error;

    /**
     * @var bool 是否是测试
     */
    public $test;

    /**
     * UploadFile constructor.
     * @param string $file_name 原文件路径
     * @param int    $error     文件上传错误编号
     * @param bool   $test      是否是测试
     */
    public function __construct($file_name, $error = 0, $test = false)
    {
        parent::__construct($file_name);

        $this->error = $error ?: UPLOAD_ERR_OK;

        empty($this->file) && $this->file = new FileSystem();
    }

    /**
     * 是否是文件上传
     *
     * @author lzl
     *
     * @return bool
     */
    public function isValid()
    {
        return is_uploaded_file($this->getPathname());
    }

    /**
     * 文件上传
     *
     * @author lzl
     *
     * @param string $path 目标文件路径
     * @param string $name 目标文件名称
     *
     * @return bool
     */
    public function move($path = '', $name = null)
    {
        if (!$path) {
            $path = RESOURCES_PATH . DS . 'image';
        }

        $targetName = $this->getTargetName($path, $name);

        if ($this->test) {
            if (false === $this->file->rename($this->getPathname(), $targetName)) {
                throw new FileException(sprintf('Could not move the file "%s" to "%s" (%s)', $this->getPathname(), $targetName));
            }

            return true;
        }

        if ($this->isValid()) {
            if (false === move_uploaded_file($this->getPathname(), $targetName)) {
                throw new FileException(sprintf('Could not move the file "%s" to "%s" (%s)', $this->getPathname(), $targetName));
            }

            return true;
        }

        throw new FileException($this->getErrorMsg());
    }

    /**
     * 获取目标文件名称
     *
     * @author lzl
     *
     * @param string $path 目标文件路径
     * @param string $name 目标文件名称
     *
     * @return string
     */
    public function getTargetName($path, $name = null)
    {
        if (!$this->file->isDir($path)) {
            if (!$this->file->makeDirectory($path, 0755, true)) {
                throw new FileException(sprintf('Could not make directory "%s"', $path));
            }
        } elseif (!$this->file->isWritable($path)) {
            throw new FileException(sprintf('Could not write in the directory "%s"', $path));
        }

        return rtrim($path, DS) . DS . ($name ? $name : $this->getBasename());
    }

    /**
     * 文件上传错误信息
     *
     * @author lzl
     *
     * @return string
     */
    public function getErrorMsg()
    {
        $msg = 'fail to upload the file "%s" due to an unknown error.';

        switch ($this->error) {
            case UPLOAD_ERR_INI_SIZE:
                $msg = 'the size of the file "%s" exceeds upload_max_filesize set by php.ini, limit "%s", given "%s"';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $msg = 'the size of the file "%s" exceeds MAX_FILE_SIZE in your form';
                break;
            case UPLOAD_ERR_PARTIAL:
                $msg = 'the file "%s" just uploaded partially';
                break;
            case UPLOAD_ERR_NO_FILE:
                $msg = 'the file "%s" not found';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $msg = 'missing the temporary directory';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $msg = 'the file "%s" could not write in the target directory';
                break;
            case UPLOAD_ERR_EXTENSION:
                $msg = 'no PHP extension set by php.ini';
                break;
        }

        return sprintf($msg, $this->getFilename(), ini_get('upload_max_filesize'), $this->getSize());
    }
}