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
    /**
     * @var string 响应状态，固定返回变量，默认为成功
     */
    public $status = STATUS_SUCCESS;

    /**
     * @var string 响应信息，固定返回变量
     */
    public $description = '';

    /**
     * @var string 响应毫秒级时间，固定返回变量
     */
    public $timestamp = '';

    /**
     * @var string 分页列表返回，返回列表总数
     */
    public $total = '';

    /**
     * @var string 预留返回字段
     */
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