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
    /* public $css = [
        'css/site.css',
    ];
    public $js = [
	'main.js',
    ]; */
	public $css = [
        'admin-lte/dist/css/AdminLTE.css',
		'admin-lte/dist/css/skins/_all-skins.min.css',
		'admin-lte/dist/css/font-awesome.min.css',
		'admin-lte/dist/css/ionicons.min.css',
    ];
    public $js = [
	'main.js',
	'admin-lte/dist/js/app.js',
	'admin-lte/plugins/jQueryUI/jquery-ui-1.10.3.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
		'yii\bootstrap\BootstrapPluginAsset'
    ];
	public $jsOptions = [
		'position' => \yii\web\View::POS_HEAD
	];
}
