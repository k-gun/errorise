<?php
declare(strict_types=1);

use Errorise\{LastErrorException, ErrorException};

class LastErrorExceptionTest extends PHPUnit\Framework\TestCase
{
    function testAll() {
        $ex = new LastErrorException();

        $this->assertSame(0, $ex->getSeverity());
        $this->assertSame('', $ex->getMessage());
        $this->assertSame('', $ex->getPureMessage());
        $this->assertNull($ex->error()->getFunction());

        // Required for "@", because of PHPUnit.
        set_error_handler(fn() => false);

        @mkdir('');
        $ex = new LastErrorException();

        restore_error_handler();

        $this->assertSame(2, $ex->getSeverity()); // E_WARNING
        $this->assertSame('mkdir(): No such file or directory', $ex->getMessage());
        $this->assertSame('No such file or directory', $ex->getPureMessage());
        $this->assertSame('mkdir', $ex->error()->getFunction());

        $this->assertInstanceOf(ErrorException::class, $ex);
        $this->assertInstanceOf(\ErrorException::class, $ex);
    }
}
