<?php
declare(strict_types=1);

use KGun\Errorise\ErrorException;

class ErrorExceptionTest extends PHPUnit\Framework\TestCase
{
    function testAll() {
        $e = new ErrorException(
            $message  = 'mkdir(): No such file or directory',
            $code     = 0,
            $severity = E_ERROR,
            $file     = __FILE__,
            $line     = 0,
        );

        $this->assertSame($severity, $e->getSeverity());
        $this->assertSame($message, $e->getMessage());
        $this->assertSame($code, $e->getCode());
        $this->assertSame($file, $e->getFile());
        $this->assertSame($line, $e->getLine());
        $this->assertSame(null, $e->getPrevious());

        $this->assertNull($e->error());
        $this->assertSame('No such file or directory', $e->getPureMessage());
    }
}
