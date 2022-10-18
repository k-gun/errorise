<?php
declare(strict_types=1);

use KGun\Errorise\{Error, ErrorHandler, ErrorException};

class ErrorHandlerTest extends PHPUnit\Framework\TestCase
{
    function testThrow() {
        $eh = new ErrorHandler();

        $this->assertNull($eh->error());

        try {
            mkdir('');
            $eh->throw();
        } catch (ErrorException $e) {
            $this->assertSame('mkdir(): No such file or directory', $e->getMessage());
            $this->assertSame('No such file or directory', $e->getPureMessage());
            $this->assertInstanceOf(\ErrorException::class, $e);
            $this->assertSame($eh->error(), $e->error());
        } finally {
            $this->assertNotNull($eh->error());
            $this->assertInstanceOf(Error::class, $eh->error());
            unset($eh);
        }

        $eh = new ErrorHandler($auto=false);

        try {
            $eh->register();
            mkdir('');
            $eh->throw();
        } catch (ErrorException $e) {
            // ..
            $this->assertInstanceOf(\ErrorException::class, $e);
        } finally {
            // ..
            $eh->register();
        }
    }

    function testThrowFor() {
        $eh = new ErrorHandler();

        try {
            mkdir('');
            $eh->throwFor('mkdir');
        } catch (ErrorException $e) {
            $this->assertSame($eh->error(), $e->error());
        } finally {
            $this->assertSame('mkdir', $eh->error()->getFunction());
            unset($eh);
        }

        $eh = new ErrorHandler();

        try {
            mkdir('');
            $eh->throwFor('chdir');
        // } catch (ErrorException) {
        //     // Nothing here to catch.
        } finally {
            $this->assertNotSame('chdir', $eh->error()->getFunction());
            unset($eh);
        }
    }

    function testThrowForMatch() {
        $eh = new ErrorHandler();

        try {
            $foo = $bar;
            $eh->throwForMatch('~\$bar~');
        } catch (ErrorException $e) {
            $this->assertSame('Undefined variable $bar', $e->getMessage());
        } finally {
            $this->assertSame(null, $eh->error()->getFunction());
            $this->assertSame('$bar', $eh->error()->getVariable());
            unset($eh);
        }

        $eh = new ErrorHandler();

        try {
            $foo = $baz;
            $eh->throwFor('~\$bar~');
        // } catch (ErrorException) {
        //     // Nothing here to catch.
        } finally {
            $this->assertNotSame('$bar', $eh->error()->getVariable());
            unset($eh);
        }
    }
}
