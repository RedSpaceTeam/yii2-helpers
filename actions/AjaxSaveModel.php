<?php

namespace redspace\helpers\actions;

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

class AjaxSaveModel extends Action
{
    /** @var ActiveRecord $model */
    public $model;

    /**
     * Атрибут содержащий название объекта
     * @var string
     */
    public $titleAttribute = 'title';

    /**
     * @var string название, например, Учебный модуль
     */
    public $title = '';

    public function run()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (is_subclass_of($this->model, ActiveRecord::class)) {

                $obj = $this->model;

                $id = Yii::$app->request->post('id');

                $model = $obj::findOne($id);

                if (!$model) {
                    $model = new $obj;
                }

                $model->load(Yii::$app->request->post());
                if ($model->validate()) {
                    $model->save();
                    if ($this->titleAttribute) {
                        return ['error' => 0, 'msg' => $this->title.' "' . $model->{$this->titleAttribute} . '" добавлен.'];
                    } else {
                        return ['error' => 0, 'msg' => 'Объект успешно изменен.'];
                    }
                } else {
                    $errors = 'Исправьте следующие ошибки: <ul>';
                    foreach ($model->getErrors() as $error) {
                        foreach ($error as $item) {
                            $errors .= '<li>' . $item . '</li>';
                        }
                    }
                    $errors .= '</ul>';
                    return ['error' => 1, 'msg' => $errors];
                }


            } else {
                throw new HttpException(500, '$model должен быть унаследован от yii\db\ActiveRecord.');
            }
        } else {
            throw new HttpException(500, 'Доступ разрешен только для AJAX-запросов.');
        }
    }
}