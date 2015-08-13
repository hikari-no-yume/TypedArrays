<?php declare(strict_types=1);

namespace ajf\TypedArrays;

class ArrayBufferTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiation() {
        $emptyBuf = new ArrayBuffer(0);
        $this->assertEquals(0, $emptyBuf->byteLength);

        $twelveBuf = new ArrayBuffer(12);
        $this->assertEquals(12, $twelveBuf->byteLength);
    }

    public function testSlicing() {
        $twelveBuf = new ArrayBuffer(12);

        // slices from 4 to end, so 8 bytes
        $fourSlice = $twelveBuf->slice(4);
        $this->assertEquals(8, $fourSlice->byteLength);

        // slices from 4 to 8, so 4 bytes
        $fourFourSlice = $twelveBuf->slice(4, 8);
        $this->assertEquals(4, $fourFourSlice->byteLength);

        // negative indices should be interpreted as index from end
        $minusOneSlice = $twelveBuf->slice(-1);
        $this->assertEquals(1, $minusOneSlice->byteLength);

        $minusFourTenSlice = $twelveBuf->slice(-4, 10);
        $this->assertEquals(2, $minusFourTenSlice->byteLength);
    }

    public function testIsView() {
        $this->assertFalse(ArrayBuffer::isView(new \StdClass));
        $this->assertTrue(ArrayBuffer::isView(new Int8Array(1)));
    }

    /**
     * @expectedException Exception
     */
    public function testMissingProperty() {
        $buf = new ArrayBuffer(0);
        $foo = $buf->foobar;
    }
}
