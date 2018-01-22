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
 */

class UnmarkDeleted extends MarkDeleted
{
    public $attributeStatement = 0;
    public $successMessage = 'Объект успешно восстановлен.';
}