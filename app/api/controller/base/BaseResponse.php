<?php
/**
 * Created by PhpStorm.
 * User: isco
 * Date: 2017/4/30
 * Time: 10:41
 */

namespace app\api\controller\base;



class BaseResponse extends Item
{
    public $status = STATUS_SUCCESS;

    public $description = '';

    public $timestamp = '';

    public $total = '';

    public $id = '';

    public function __construct()
    {
        $key = array(
            'status' => 's',
            'description' => 'd',
            'timestamp' => 't',
        );

        $this->setOption('key', $key);
    }
}