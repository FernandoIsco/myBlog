<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/1
 * Time: 10:44
 */

namespace app\api\controller;


class Common extends Api
{
    /**
     * 获取模型
     * @param string $model 模型名称
     * @return mixed
     */
    public function getModel($model)
    {
        $model = ucfirst($model);
        if (empty($this->models[$model])) {
            $class = 'app\api\model\\' .$model;
            if (!class_exists($class)) {
                $this->setApiResponse(STATUS_SERVICE_ERROR);
            }

            $this->models[$model] = new $class($this->request);
        }

        return $this->models[$model];
    }

    /**
     * 检查是否登录
     *
     * @return mixed
     */
    public function checkLogin()
    {
        return $this->getModel('token')->checkLogin($this->getSession());
    }
}