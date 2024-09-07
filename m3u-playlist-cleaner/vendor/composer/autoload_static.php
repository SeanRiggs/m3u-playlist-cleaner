<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1111e4319b62ac622df94c10b32a2e60
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'M3uParser\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'M3uParser\\' => 
        array (
            0 => __DIR__ . '/..' . '/gemorroj/m3u-parser/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1111e4319b62ac622df94c10b32a2e60::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1111e4319b62ac622df94c10b32a2e60::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1111e4319b62ac622df94c10b32a2e60::$classMap;

        }, null, ClassLoader::class);
    }
}