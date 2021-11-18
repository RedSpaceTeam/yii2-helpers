<?php

namespace redspace\helpers\assets;

use yii\web\AssetBundle;

class NotyAsset extends AssetBundle
{

    public $sourcePath = '@vendor/redspace/helpers/vendors';

    public $js = [
        'noty/'
    ];
}