<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/8
 * Time: 16:35
 */

namespace app\api\model;



class Document extends BaseModel
{
    const DOC_SEARCHED = 1;

    public function getDocuments($where = array(), $field = array())
    {
        $data = $this->getList($where, $field, array('parent_id' => 'asc'));

        $return = array();
        if ($data) {
            foreach ($data as $item) {
                $item->searched = self::DOC_SEARCHED;
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

    public function updateDoc($data, $where)
    {
        return $this->edit($data, $where);
    }
}