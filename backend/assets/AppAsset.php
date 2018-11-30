<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'static/layuiadmin/layui/css/layui.css',
        'static/layuiadmin/style/admin.css',
    ];
    public $js = [
        'static/layuiadmin/layui/layui.js'
    ];
    public $depends = [

    ];
}
