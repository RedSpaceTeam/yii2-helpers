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

class MarkDeleted extends Action
{
    /** @var ActiveRecord $model */
    public $model;

    /** @var string $attribute */
    public $attribute;

    public $attributeStatement = 1;

    /** @var callable $access  */
    public $access;

    public $successMessage = 'Объект успешно удален.';
    public $isLoadTitle = false;


    public function run($id = null)
    {
        if (Yii::$app->request->isAjax) {
            if (is_subclass_of($this->model, ActiveRecord::class)) {

                $obj = $this->model;
                $model = $obj::findOne($id);
                if ($model) {
                    if ($model->hasAttribute($this->attribute)) {
                        Yii::$app->response->format = Response::FORMAT_JSON;

                        $checkAccess = true;
                        if (is_callable($this->access)) {
                            $checkAccess = call_user_func($this->access);
                        }

                        if ($checkAccess) {
                            $this->markObject($model);
                            if ($model->validate([$this->attribute])) {
                                $model->save(true, [$this->attribute]);
                                return ['error' => 0, 'msg' => $this->successMessage];
                            } else {
                                return ['error' => 1, 'msg' => 'Произошла ошибка.'];
                            }
                        } else {
                            return ['error' => 1, 'msg' => 'Нет доступа.'];
                        }

                    } else {
                        throw new HttpException(500, 'Не существует свойства $attribute в модели.');
                    }
                }
            } else {
                throw new HttpException(500, '$model должен быть унаследован от yii\db\ActiveRecord.');
            }
        } else {
            throw new HttpException(500, 'Доступ разрешен только для AJAX-запросов.');
        }
    }


    public function markObject($model)
    {
        $model->{$this->attribute} = $this->attributeStatement;
        return $model;
    }
}