<?php

/**
 * Все элементы модели, кроме $ignoreAttributes проходят через фильтр trim()
 */

namespace redspace\helpers\behaviors;

use Yii;
use yii\helpers\VarDumper;
use function in_array;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\validators\FilterValidator;
use yii\validators\Validator;

class TrimAttributesBehavior extends Behavior
{
    /**
     * @var \yii\db\ActiveRecord
     */
    public $owner;

    /**
     * Какие атрибуты нужно игнорировать
     * @var array
     */
    public $ignoreAttributes = [];

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
        $attributes = [];

        $temp = array_keys($this->owner->getAttributes());
        foreach ($temp as $item) {
            if (!in_array($item, $this->ignoreAttributes) && !is_object($item)) {
                $attributes[] = $item;
            }
        }

        $this->owner->validators->offsetSet(-1, Validator::createValidator(FilterValidator::class, $this->owner, $attributes, ['filter' => 'trim']));
        $this->owner->validators->ksort();
    }
}