<?php

namespace redspace\helpers\behaviors;

use Yii;
use yii\base\Behavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\helpers\FileHelper;
use yii\web\HttpException;
use yii\web\UploadedFile;

class DateTimeBehaviour extends TimestampBehavior
{
    protected function getValue($event)
    {
        if ($this->value === null) {
            $dateTime = new \DateTime();
            return $dateTime->format('Y-m-d H:i:s');
        }

        return parent::getValue($event);
    }
}
