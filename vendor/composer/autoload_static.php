<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd2a7a4d4a3bcb6735d868883d53a670f
{
    public static $prefixLengthsPsr4 = array (
        'U' => 
        array (
            'UM\\' => 3,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'UM\\' => 
        array (
            0 => __DIR__ . '/../..' . '/UM',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd2a7a4d4a3bcb6735d868883d53a670f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd2a7a4d4a3bcb6735d868883d53a670f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd2a7a4d4a3bcb6735d868883d53a670f::$classMap;

        }, null, ClassLoader::class);
    }
}
