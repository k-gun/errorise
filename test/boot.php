<?php
namespace KGun\Errorise;

// Register autoload.
spl_autoload_register(function ($name) {
    static $prefix = __NAMESPACE__ . '\\';
    if (stripos($name, $prefix) === 0) {
        $name = strtr(substr($name, strlen($prefix)), '\\', '/');
        $file = realpath(__DIR__ . '/../src/' . $name . '.php');
        $file && require $file;
    }
});
