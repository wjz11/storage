<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc9e8c8c3f55f3b1386ef23f536ed35fb
{
    public static $prefixLengthsPsr4 = array (
        'i' => 
        array (
            'inc\\' => 4,
        ),
        'a' => 
        array (
            'admin\\' => 6,
        ),
        'M' => 
        array (
            'Medoo\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'inc\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
        'admin\\' => 
        array (
            0 => __DIR__ . '/../..' . '/admin/module',
        ),
        'Medoo\\' => 
        array (
            0 => __DIR__ . '/..' . '/catfan/medoo/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'M' => 
        array (
            'Monolog' => 
            array (
                0 => __DIR__ . '/..' . '/monolog/monolog/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc9e8c8c3f55f3b1386ef23f536ed35fb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc9e8c8c3f55f3b1386ef23f536ed35fb::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitc9e8c8c3f55f3b1386ef23f536ed35fb::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}