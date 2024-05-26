<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdc8231a12a9f67e1901f979394d55975
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
        'A' => 
        array (
            'Automattic\\WooCommerce\\Blocks\\' => 30,
            'Automattic\\Jetpack\\Autoloader\\' => 30,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
        'Automattic\\WooCommerce\\Blocks\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Automattic\\Jetpack\\Autoloader\\' => 
        array (
            0 => __DIR__ . '/..' . '/automattic/jetpack-autoloader/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdc8231a12a9f67e1901f979394d55975::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdc8231a12a9f67e1901f979394d55975::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
