<?php
declare(strict_types=1);

use KGun\Errorise\Error;

class ErrorTest extends PHPUnit\Framework\TestCase
{
    function testAll() {
        $e = new Error($data = [
            'severity' => E_ERROR,
            'message'  => 'mkdir(): No such file or directory',
            'file'     => __FILE__,
            'line'     => 0,
        ]);

        $this->assertEquals($data, $e->data());

        $this->assertSame($data['severity'], $e->getSeverity());
        $this->assertSame($data['message'], $e->getMessage());
        $this->assertSame($data['file'], $e->getFile());
        $this->assertSame($data['line'], $e->getLine());

        $this->assertSame('mkdir', $e->getFunction());
        $this->assertSame(null, $e->getVariable());
    }
}
