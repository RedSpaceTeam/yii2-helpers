<?php

namespace redspace\helpers\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use yii\helpers\FileHelper;
use yii\web\HttpException;
use yii\web\UploadedFile;

class FileUploadBehavior extends Behavior
{
    /**
     * @var string File storage path
     */
    public $uploadPath = '@backend/web/uploads';

    /**
     * @var string attribute of UploadedFile instance
     */
    public $fileAttribute = 'file';

    /**
     * @var string attribute of path to uploaded file on the storage
     */
    public $storageAttribute = 'file_src';

    /**
     * @var string attribute of original filename
     */
    public $nameAttribute = 'name';

    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'uploadFile',
            BaseActiveRecord::EVENT_AFTER_DELETE => 'deleteFile',
        ];
    }

    public function uploadFile()
    {
        $file = $this->owner->{$this->fileAttribute};
        if ($file) {
            if (!$file instanceof UploadedFile) {
                throw new HttpException(500, '$file ожидается унаследованный от UploadedFile');
            }

            $filepath = date('Y/m/d') . '/' . uniqid() . '.' . $file->extension;
            $fullpath = Yii::getAlias($this->uploadPath) . '/' . $filepath;
            if (!is_dir($dir = dirname($fullpath))) {
                FileHelper::createDirectory($dir);
            }

            if (!$file->saveAs($fullpath)) {
                return false;
            }

            $this->owner->{$this->storageAttribute} = $filepath;
            if ($this->nameAttribute !== false) {
                $this->owner->{$this->nameAttribute} = $file->name;
            }

            return true;
        }
    }
    public function deleteFile()
    {
        if (file_exists($filepath = Yii::getAlias($this->uploadPath) . '/' . $this->owner->{$this->storageAttribute})) {
                unlink($filepath);
        }
    }

}
