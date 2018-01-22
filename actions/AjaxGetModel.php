<?php

namespace backend\actions;

use yii\base\Action;
use yii\db\ActiveRecord;
use yii\web\HttpException;
use yii\web\Response;
use Yii;

/**
 * Created by PhpStorm.
 * User: smirnov
 * Date: 18.12.2017
 * Time: 1:26
 *
 *
 */

class AjaxGetModel extends Action
{
    /** @var ActiveRecord $model */
    public $model;

    public function run($id = null)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (is_subclass_of($this->model, ActiveRecord::class)) {
                $obj = $this->model;
                $model = $obj::findOne($id);

                if (!$model) {
                    $model = new $obj;
                    return ['error' => 0, 'exists' => 0, 'data' => $model->attributes];
                } else {
                    return ['error' => 1, 'exists' => 1, 'data' => $model->attributes];
                }

            } else {
                throw new HttpException(500, '$model должен быть унаследован от yii\db\ActiveRecord.');
            }
        } else {
            throw new HttpException(500, 'Доступ разрешен только для AJAX-запросов.');
        }
    }
}