<?php
// Run:
// vendor/bin/phpunit --bootstrap=./boot.php ./unit
// vendor/bin/phpunit --bootstrap=./boot.php ./unit --colors --testdox
namespace KGun\Errorise;

// Register autoload.
spl_autoload_register(function ($name) {
    if (strpos($name, __NAMESPACE__) === 0) {
        $name = strtr(substr($name, strlen(__NAMESPACE__)), '\\', '/');
        $file = realpath(__DIR__ . '/../src/' . $name . '.php');
        $file && require $file;
    }
});
