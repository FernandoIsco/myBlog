<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/1
 * Time: 10:44
 */

namespace app\api\controller;


use app\api\model\BaseModel;

class Common extends Api
{
    protected $models;

    /**
     * 获取模型
     * @param string $model 模型名称
     * @return mixed
     */
    public function getModel($model = '')
    {
        if (!$model) {
            if (isset($this->models['__base__'])) {
                return $this->models['__base__'];
            }

            return $this->models['__base__'] = new BaseModel($this->request);
        }

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