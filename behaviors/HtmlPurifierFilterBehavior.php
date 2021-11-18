<?php

/**
 * Избавляемся от XSS в атрибутах моделей
 * Заодно добавляем фильтр trim
 */

namespace redspace\helpers\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\validators\FilterValidator;
use yii\validators\Validator;

class HtmlPurifierFilterBehavior extends Behavior
{
    /**
     * @var \yii\db\ActiveRecord
     */
    public $owner;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }



    /**
     * Before model validate event
     *
     * @param \yii\base\ModelEvent $event
     */
    public function beforeValidate($event)
    {
        $this->owner->validators[] = Validator::createValidator(FilterValidator::class, $this->owner, array_keys($this->owner->getAttributes()), ['filter' => '\yii\helpers\HtmlPurifier::process']);
        $this->owner->validators[] = Validator::createValidator(FilterValidator::class, $this->owner, array_keys($this->owner->getAttributes()), ['filter' => 'trim']);
    }
}