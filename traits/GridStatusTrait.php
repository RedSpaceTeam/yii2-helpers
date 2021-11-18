<?php

namespace redspace\helpers\traits;


/**
 * Created by PhpStorm.
 * User: smirnov
 * Date: 18.12.2017
 * Time: 2:19
 *
 * @property boolean $is_deleted
 */

trait GridStatusTrait
{
    public static function isDeletedLabels() {
        return [
            0 => 'активен',
            1 => 'удален'
        ];
    }

    public static function isDeletedLabelClasses() {
        return [
            0 => 'label label-primary',
            1 => 'label label-danger',
        ];
    }

    public function isDeletedLabel()
    {
        return '<span class="' . self::isDeletedLabelClasses()[$this->is_deleted] . '">' . self::isDeletedLabels()[$this->is_deleted] . '</span>';
    }
}