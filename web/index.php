<?php
require(__DIR__.'/../sword.php');
defined('SHOW_ARR') or define('SHOW_ARR', false);
deleteLogs();
tracelog('Yiisa begin');
SHOW_ARR && tracelog(print_r($_SERVER,true));
// comment out the following two lines when deployed to production
tracelog('开启YII_DEBUG');
defined('YII_DEBUG') or define('YII_DEBUG', true);
//定义开发环境
tracelog('定义开发环境');
defined('YII_ENV') or define('YII_ENV', 'dev');

//自动加载第三方类库
tracelog('引入第三方类库自动加载文件');
require(__DIR__ . '/../vendor/autoload.php');
tracelog('引入Yii框架入口文件');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
tracelog('引入web 配置文件');
$config = require(__DIR__ . '/../config/web.php');
SHOW_ARR && tracelog(print_r($config,true));
tracelog('yii\web\Application->run() ,跑应用!');
(new yii\web\Application($config))->run();
