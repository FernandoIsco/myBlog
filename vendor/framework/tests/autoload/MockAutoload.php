<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/17
 * Time: 11:08
 */

namespace tests\autoload;

use Emilia\Autoload;

require_once '../src/foundation/Autoload.php';

class MockAutoload extends Autoload
{
    public $files;

    public function setFiles($files)
    {
        $this->files = $files;
    }

    public function requireFile($file)
    {
        return in_array($file, $this->files);
    }
}