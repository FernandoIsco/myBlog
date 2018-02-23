<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/9
 * Time: 14:02
 */

namespace Emilia\cache\driver;


use Emilia\cache\base\BaseStore;
use Emilia\cache\base\Store;
use Emilia\config\Config;
use Emilia\FileSystem;
use Emilia\log\Logger;

class FileStore extends BaseStore implements Store
{
    /**
     * @var FileSystem 文件系统对象
     */
    private $file;

    /**
     * @var string 路径
     */
    private $path;

    public function __construct(FileSystem $fileSystem, $path = '')
    {
        $this->file = $fileSystem;

        $this->setPrefix($path);
    }

    public function get($key)
    {
        $data = unserialize($this->file->get($this->getPrefix() . $key));

        if (empty($data)) {
            return false;
        }

        if ($data['time'] < time()) {
            $this->remove($key);

            return false;
        }

        return $data['data'];
    }

    public function put($key, $value, $expire)
    {
        $value = array(
            'time' => $this->expiration($expire),
            'data' => $value
        );

        if ($this->checkCacheDirectory()) {
            $this->file->write($this->getPrefix() . $key, serialize($value), true);
        }

        return true;
    }

    public function forever($key, $value)
    {
        return $this->put($key, $value, 9999999); // TODO 最大时间戳
    }

    public function increment($key, $value = 1)
    {
        return true;
    }

    public function decrement($key, $value = 1)
    {
        return true;
    }

    public function remove($key)
    {
        if ($this->file->exists($this->getPrefix() . $key)) {
            return $this->file->unlink($this->getPrefix() . $key);
        } else {
            Logger::record(sprintf("the file %s is not found", $this->getPrefix() . $key));

            return false;
        }
    }

    public function flush()
    {
        if ($this->file->isDir($this->getPrefix()) && $this->getPrefix() != DS) {
            $this->file->removeDirectory($this->getPrefix());
        }

        return true;
    }

    /**
     * 设置缓存路径
     *
     * @author lzl
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPrefix($path = '')
    {
        $config = Config::getConfig('fileStore');

        $this->path = rtrim((empty($path) ? $config['path'] : $path), DS) . DS;

        return $this;
    }

    /**
     * 获取缓存路径
     *
     * @author lzl
     *
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->path;
    }

    /**
     * 检查缓存路径，无则创建
     *
     * @author lzl
     *
     * @return bool
     */
    public function checkCacheDirectory()
    {
        if (!$this->file->isDir($this->getPrefix())) {
            return $this->file->makeDirectory($this->getPrefix(), 0777, true);
        }

        return true;
    }
}