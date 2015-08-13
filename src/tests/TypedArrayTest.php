<?php declare(strict_types=1);

namespace ajf\TypedArrays;

class TypedArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiation() {
        $emptyArr = new Uint8Array(0);
        $this->assertEquals(0, $emptyArr->length);

        $twelveArr = new Uint8Array(12);
        $this->assertEquals(12, $twelveArr->length);

        $arrayArr = new Uint8Array([1, 2, 3, 4]);
        $this->assertEquals(4, $arrayArr->length);
        $this->assertEquals(1, $arrayArr[0]);
        $this->assertEquals(2, $arrayArr[1]);
        $this->assertEquals(3, $arrayArr[2]);
        $this->assertEquals(4, $arrayArr[3]);

        $arrayArrArr = new Uint8Array($arrayArr);
        $this->assertEquals(4, $arrayArr->length);
        $this->assertEquals(1, $arrayArr[0]);
        $this->assertEquals(2, $arrayArr[1]);
        $this->assertEquals(3, $arrayArr[2]);
        $this->assertEquals(4, $arrayArr[3]);

        $emptyBuf = new ArrayBuffer(0);
        $emptyBufArr = new Uint8Array($emptyBuf);
        $this->assertEquals(0, $emptyBufArr->length);

        $twelveBuf = new ArrayBuffer(12);
        $twelveBufFourArr = new Uint8Array($twelveBuf, 4);
        $this->assertEquals(8, $twelveBufFourArr->length);

        $twelveBufFourFourArr = new Uint8Array($twelveBuf, 4, 4);
        $this->assertEquals(4, $twelveBufFourFourArr->length);
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testInitOffsetExceeded() {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf, 1);
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testInitLengthExceeded() {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf, 0, 2);
    }
    
    /**
     * @expectedException OutOfBoundsException
     */
    public function testInitOffsetAndLengthExceeded() {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf, 1, 1);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInitOffsetNonAlignment() {
        // Uint16Array has alement size of 2, so expects offset multiple of 2
        $threeBuf = new ArrayBuffer(3);
        $threeBufArr = new Uint16Array($threeBuf, 1);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInitLengthNonAlignment() {
        // Uint16Array has alement size of 2, so expects length multiple of 2
        $threeBuf = new ArrayBuffer(3);
        $threeBufArr = new Uint16Array($threeBuf);
    }

    public function testRead0xff() {
        $buf = new ArrayBuffer(4);
        $uint8Arr = new Uint8Array($buf);
        $int8Arr = new Int8Array($buf);
        $uint16Arr = new Uint16Array($buf);
        $int16Arr = new Int16Array($buf);
        $uint32Arr = new Uint32Array($buf);
        $int32Arr = new Int32Array($buf);

        $uint8Arr[0] = 0xff;
        $uint8Arr[1] = 0xff;
        $uint8Arr[2] = 0xff;
        $uint8Arr[3] = 0xff;

        $this->assertEquals(0xff, $uint8Arr[0]);
        $this->assertEquals(-1, $int8Arr[0]);
        $this->assertEquals(0xffff, $uint16Arr[0]);
        $this->assertEquals(-1, $int16Arr[0]);
        $this->assertEquals(0xffffffff, $uint32Arr[0]);
        $this->assertEquals(-1, $int32Arr[0]);
    }

    public function testSet0xff() {
        $fourBuf = new ArrayBuffer(4);
        $uint32FourBufArr = new Uint32Array($fourBuf);
        $uint8FourBufArr = new Uint8Array($fourBuf);

        $uint32FourBufArr[0] = 0xffffffff;

        $this->assertEquals(0xff, $uint8FourBufArr[0]);
        $this->assertEquals(0xff, $uint8FourBufArr[1]);
        $this->assertEquals(0xff, $uint8FourBufArr[2]);
        $this->assertEquals(0xff, $uint8FourBufArr[3]);

        $fourBuf = new ArrayBuffer(4);
        $int32FourBufArr = new Int32Array($fourBuf);
        $uint8FourBufArr = new Uint8Array($fourBuf);

        $int32FourBufArr[0] = -1;

        $this->assertEquals(0xff, $uint8FourBufArr[0]);
        $this->assertEquals(0xff, $uint8FourBufArr[1]);
        $this->assertEquals(0xff, $uint8FourBufArr[2]);
        $this->assertEquals(0xff, $uint8FourBufArr[3]);

        $twoBuf = new ArrayBuffer(2);
        $uint16TwoBufArr = new Uint16Array($twoBuf);
        $uint8TwoBufArr = new Uint8Array($twoBuf);

        $uint16TwoBufArr[0] = 0xffff;

        $this->assertEquals(0xff, $uint8TwoBufArr[0]);
        $this->assertEquals(0xff, $uint8TwoBufArr[1]);

        $twoBuf = new ArrayBuffer(2);
        $int16TwoBufArr = new Int16Array($twoBuf);
        $uint8TwoBufArr = new Uint8Array($twoBuf);

        $int16TwoBufArr[0] = -1;

        $this->assertEquals(0xff, $uint8TwoBufArr[0]);
        $this->assertEquals(0xff, $uint8TwoBufArr[1]);

        $oneBuf = new ArrayBuffer(1);
        $uint8OneBufArr = new Uint8Array($oneBuf);

        $uint8OneBufArr[0] = 255;

        $this->assertEquals(0xff, $uint8OneBufArr[0]);

        $oneBuf = new ArrayBuffer(1);
        $int8OneBufArr = new Int8Array($oneBuf);
        $uint8OneBufArr = new Uint8Array($oneBuf);

        $int8OneBufArr[0] = -1;

        $this->assertEquals(0xff, $uint8OneBufArr[0]);
    }

    public function testSetAll256() {
        foreach ([Int8Array::class, Int16Array::class, Int32Array::class, Float32Array::class, Float64Array::class] as $class) {
            $arr = new $class(256);
            $pos = 0;
            for ($i = -128; $i < 128; $i++) {
                $arr[$pos] = $i;
                $pos++;
            }
            $pos = 0;
            for ($i = -128; $i < 128; $i++) {
                $this->assertEquals($arr[$pos], $i);
                $pos++;
            }
        }

        foreach ([Uint8Array::class, Uint16Array::class, Uint32Array::class] as $class) {
            $arr = new $class(256);
            $pos = 0;
            for ($i = 0; $i < 256; $i++) {
                $arr[$pos] = $i;
                $pos++;
            }
            $pos = 0;
            for ($i = 0; $i < 256; $i++) {
                $this->assertEquals($arr[$pos], $i);
                $pos++;
            }
        }
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testGetOffsetLessThanZero() {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $foo = $oneBufArr[-1];
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testGetOffsetGreaterThanLength() {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $foo = $oneBufArr[1];
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testSetOffsetLessThanZero() {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $oneBufArr[-1] = 12;
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testSetOffsetGreaterThanLength() {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $oneBufArr[1] = 12;
    }

    /**
     * @expectedException DomainException
     */
    public function testUnset() {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        unset($oneBufArr[1]);
    }

    public function testIsset() {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $this->assertFalse(isset($oneBufArr[-1]));
        $this->assertTrue(isset($oneBufArr[0]));
        $this->assertFalse(isset($oneBufArr[1]));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testIssetNonInteger() {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $foo = isset($oneBufArr["test"]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetNonInteger() {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $foo = $oneBufArr["test"];
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetNonInteger() {
        $oneBuf = new ArrayBuffer(1);
        $oneBufArr = new Uint8Array($oneBuf);
        $oneBufArr["test"] = 12;
    }

    // TODO: test set/subarray/properties and actual data storage

    /**
     * @expectedException Exception
     */
    public function testMissingProperty() {
        $buf = new ArrayBuffer(0);
        $foo = $buf->foobar;
    }
}
