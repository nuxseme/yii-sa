<?php
/**
 * Yii bootstrap file.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
tracelog('引入BaseYii.php');

require(__DIR__ . '/BaseYii.php');
tracelog('BaseYii.php 设置static classMap 用于保存类映射关系');
tracelog('BaseYii.php 设置static app 用于保存应用实例');
tracelog('BaseYii.php 设置static aliases 用于保存别名');
tracelog('BaseYii.php 设置static container 用于保存容器实例');
tracelog('BaseYii.php 设置static _logger 用于保存log实例');

/**
 * Yii is a helper class serving common framework functionalities.
 *
 * It extends from [[\yii\BaseYii]] which provides the actual implementation.
 * By writing your own Yii class, you can customize some functionalities of [[\yii\BaseYii]].
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Yii extends \yii\BaseYii
{
}
tracelog('添加自动注册函数为Yii::autoload');
spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = require(__DIR__ . '/classes.php');
tracelog('Yii框架预加载类到classMap');
tracelog('加载container  容器');
Yii::$container = new yii\di\Container();
