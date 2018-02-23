<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/8
 * Time: 16:35
 */

namespace app\api\model;


use Emilia\mvc\Model;

class Document extends Model
{
    public function getList($where = array(), $field = array())
    {
        $data = $this->order(array('parent_id' => 'asc'))->select($where, $field);

        $return = array();
        if ($data) {
            foreach ($data as $item) {
                if (!$item->parent_id) {
                    $return[$item->id] = $item;
                } else {
                    !isset($return[$item->parent_id]->sub) && $return[$item->parent_id]->sub = array();

                    array_push($return[$item->parent_id]->sub, $item);
                }
            }
        }

        return array_values($return);
    }

    public function add()
    {

    }
}