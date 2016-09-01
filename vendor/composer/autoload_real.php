<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitbb2f4b3af011f8354c128328c3ac88c0
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    public static function getLoader()
    {
        if (null !== self::$loader) {
            tracelog('已经加载了加载器就直接返回加载器实例');
            return self::$loader;
        }
        tracelog('注册自动加载器');
        spl_autoload_register(array('ComposerAutoloaderInitbb2f4b3af011f8354c128328c3ac88c0', 'loadClassLoader'), true, true);
        //这里会调用loadClassLoader 返回当前目录下的ClassLoader 实例
        tracelog('调用loadClassLoader 返回当前目录下的ClassLoader 实例');
        self::$loader = $loader = new \Composer\Autoload\ClassLoader();
        spl_autoload_unregister(array('ComposerAutoloaderInitbb2f4b3af011f8354c128328c3ac88c0', 'loadClassLoader'));
        $useStaticLoader = PHP_VERSION_ID >= 50600 && !defined('HHVM_VERSION');
        if ($useStaticLoader) {
            require_once __DIR__ . '/autoload_static.php';
            // 通常不会进入这里  这里给实例化的$loader 的属性赋值
            tracelog('通常不会进入这里  这里给实例化的$loader 的属性赋值');
            call_user_func(\Composer\Autoload\ComposerStaticInitbb2f4b3af011f8354c128328c3ac88c0::getInitializer($loader));
        } else {
            //引入ezyang phpspec类库命名空间对应的目录路径
            /**
             * Yii 有3种进入类库的方式  
             * autoload_namespace
             * autoload_psr4
             * autoload_classmap
             */
            tracelog('Yii 有3种进入类库的方式  autoload_namespace autoload_psr4 autoload_classmap');
            tracelog('保存auotload_namespace的命名空间与目录映射');
            $map = require __DIR__ . '/autoload_namespaces.php';
            foreach ($map as $namespace => $path) {
                $loader->set($namespace, $path);
            }
            tracelog('保存autoload_psr4命名空间与目录的映射');
            $map = require __DIR__ . '/autoload_psr4.php';
            foreach ($map as $namespace => $path) {
                $loader->setPsr4($namespace, $path);
            }
            tracelog('保存autoload_classmap命名空间与目录的映射');
            $classMap = require __DIR__ . '/autoload_classmap.php';
            if ($classMap) {
                $loader->addClassMap($classMap);
            }
        }
        tracelog('ClassLoader.php=>register 修改自动注册函数');
        $loader->register(true);

        if ($useStaticLoader) {
            $includeFiles = Composer\Autoload\ComposerStaticInitbb2f4b3af011f8354c128328c3ac88c0::$files;
        } else {
            $includeFiles = require __DIR__ . '/autoload_files.php';
        }
        foreach ($includeFiles as $fileIdentifier => $file) {
            composerRequirebb2f4b3af011f8354c128328c3ac88c0($fileIdentifier, $file);
        }

        return $loader;
    }
}

function composerRequirebb2f4b3af011f8354c128328c3ac88c0($fileIdentifier, $file)
{
    if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        require $file;

        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;
    }
}
