<?php
declare(strict_types=1);

use KGun\Errorise\ErrorException;

class ErrorExceptionTest extends PHPUnit\Framework\TestCase
{
    function testAll() {
        $ex = new ErrorException(
            $message  = 'mkdir(): No such file or directory',
            $code     = 0,
            $severity = E_ERROR,
            $file     = __FILE__,
            $line     = 0,
        );

        $this->assertSame($severity, $ex->getSeverity());
        $this->assertSame($message, $ex->getMessage());
        $this->assertSame($code, $ex->getCode());
        $this->assertSame($file, $ex->getFile());
        $this->assertSame($line, $ex->getLine());
        $this->assertSame(null, $ex->getPrevious());

        $this->assertNull($ex->error());
        $this->assertSame('No such file or directory', $ex->getPureMessage());

        $this->assertInstanceOf(\ErrorException::class, $ex);
    }
}
