<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit74bd0557f78dbcb77e5d708fa2b64a44
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
        'A' => 
        array (
            'Automattic\\WooCommerce\\Admin\\' => 29,
            'Automattic\\Jetpack\\Autoloader\\' => 30,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
        'Automattic\\WooCommerce\\Admin\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Automattic\\Jetpack\\Autoloader\\' => 
        array (
            0 => __DIR__ . '/..' . '/automattic/jetpack-autoloader/src',
        ),
    );

    public static $classMap = array (
        'Automattic\\Jetpack\\Autoloader\\AutoloadGenerator' => __DIR__ . '/..' . '/automattic/jetpack-autoloader/src/AutoloadGenerator.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit74bd0557f78dbcb77e5d708fa2b64a44::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit74bd0557f78dbcb77e5d708fa2b64a44::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit74bd0557f78dbcb77e5d708fa2b64a44::$classMap;

        }, null, ClassLoader::class);
    }
}
