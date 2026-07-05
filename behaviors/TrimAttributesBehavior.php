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

        // Берём только изменённые атрибуты и только строковые значения:
        // trim() приводит к строке, поэтому на int/null его натравливать нельзя —
        // иначе id/*_id меняют тип и ложно попадают в getDirtyAttributes().
        // Плоские формы (yii\base\Model) не имеют getDirtyAttributes() — берём все атрибуты.
        $dirty = method_exists($this->owner, 'getDirtyAttributes')
            ? $this->owner->getDirtyAttributes()
            : $this->owner->getAttributes();
        foreach (array_keys($dirty) as $name) {
            if (in_array($name, $this->ignoreAttributes)) {
                continue;
            }
            if (!is_string($this->owner->$name)) {
                continue;
            }
            $attributes[] = $name;
        }

        if (empty($attributes)) {
            return;
        }

        $this->owner->validators->offsetSet(-1, Validator::createValidator(FilterValidator::class, $this->owner, $attributes, ['filter' => 'trim']));
        $this->owner->validators->ksort();
    }
}