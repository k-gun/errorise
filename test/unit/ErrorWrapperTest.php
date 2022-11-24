<?php
declare(strict_types=1);

use Errorise\{ErrorWrapper, ErrorException};

class ErrorWrapperTest extends PHPUnit\Framework\TestCase
{
    function testWrap() {
        $ret = ErrorWrapper::wrap(function () {
            $fp = fopen('absent-file', 'r');
            $fp && fclose($fp);
            return !!$fp;
        }, $e1);

        $this->assertFalse($ret);

        $this->assertInstanceOf(ErrorException::class, $e1);
        $this->assertInstanceOf(\ErrorException::class, $e1);

        $this->assertSame('fopen(absent-file): Failed to open stream: No such file or directory',
            $e1->getMessage());
        $this->assertSame(__FILE__, $e1->getFile());
        $this->assertSame(10, $e1->getLine());
        $this->assertSame('fopen', $e1->error()->getFunction());

        $ret = ErrorWrapper::wrap(function () {
            $fp = fopen(__FILE__, 'r');
            $fp && fclose($fp);
            return !!$fp;
        }, $e2);

        $this->assertTrue($ret);
        $this->assertNull($e2);
    }
}
