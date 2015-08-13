<?php declare(strict_types=1);

namespace ajf\TypedArrays;

class ArrayBufferViewTest extends \PHPUnit_Framework_TestCase
{
    public function testProperty() {
        $buffer = new ArrayBuffer(16);
        $uselessView = new class($buffer) extends ArrayBufferView
        {
            public function __construct(ArrayBuffer $buf) {
                $this->buffer = $buf;
                $this->byteOffset = 0;
                $this->byteLength = $buf->byteLength;
            }
        };

        $this->assertEquals($buffer, $uselessView->buffer);
        $this->assertEquals($buffer->byteLength, $uselessView->byteLength);
        $this->assertEquals(0, $uselessView->byteOffset);
    }

    /**
     * @expectedException Exception
     */
    public function testMissingProperty() {
        $uselessView = new class extends ArrayBufferView
        {
        };

        $foo = $uselessView->foobar;
    }
}
