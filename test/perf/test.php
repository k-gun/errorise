<?php
// Run:
// php -f test.php
// php -f test.php 100000
namespace KGun\Errorise;

require __DIR__ . '/../boot.php';

if (PHP_SAPI != 'cli') {
    echo 'Run this script via CLI', PHP_EOL;
    exit(1);
}

$limit = intval($_SERVER['argv'][1] ?? 1000);
if ($limit < 1) {
    echo 'Limit must be greater that 1', PHP_EOL;
    exit(1);
}

function run($func) {
    global $limit;
    $l = $limit;

    $start = microtime(true);
    while (--$l) {
        $func();
    }
    return microtime(true) - $start;
}
function format($result) {
    return $result >= 1 ? 's' : 'ms';
}

// Default test with "@".
$result = run(function () {
    @mkdir('');
});

// run(1000): 0.005056ms
printf('run(%d): %F%s%s', $limit, $result, format($result), PHP_EOL);

// Errorise test.
$result = run(function () {
    $eh = new ErrorHandler();
    try {
        mkdir('');
        $eh->throw();
    } catch (ErrorException $e) {
        //
    } finally {
        unset($eh);
    }
});

// run(1000): 0.030963ms
printf('run(%d): %F%s%s', $limit, $result, format($result), PHP_EOL);
