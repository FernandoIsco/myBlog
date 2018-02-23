<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/21
 * Time: 14:15
 */

namespace Emilia\session\handlers;


use Emilia\FileSystem;

class FileSessionHandler implements \SessionHandlerInterface
{
    /**
     * @var object 文件管理
     */
    protected $file;

    /**
     * @var string 存储路径
     */
    protected $save_path;

    public function open($save_path, $name)
    {
        empty($this->file) && $this->file = new FileSystem();

        $this->save_path = $save_path ? $save_path : APP_STORAGE_PATH . 'temp' . DS;

        return true;
    }

    public function write($session_id, $session_data)
    {
        $this->file->write($this->save_path . $session_id, $session_data);

        return true;
    }

    public function read($session_id)
    {
        $file = $this->save_path . $session_id;

        if ($this->file->exists($file)) {
            return $this->file->get($file);
        }

        return '';
    }

    public function close()
    {
        return true;
    }

    public function destroy($session_id)
    {
        return $this->file->unlink($this->save_path . $session_id);
    }

    public function gc($maxlifetime)
    {
        foreach (glob($this->save_path . "*") as $file) {
            if ($this->file->lastModified($file) + $maxlifetime < time() && $this->file->exists($file)) {
                $this->file->unlink($file);
            }
        }

        return true;
    }
}