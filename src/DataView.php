<?php declare(strict_types=1);

namespace ajf\TypedArrays;

// https://www.khronos.org/registry/typedarray/specs/latest/#8
class DataView extends ArrayBufferView
{
    private static function isMachineLittleEndian(): bool {
        static $isMachineLittleEndian = NULL;
        if (\is_null($isMachineLittleEndian)) {
            $testBytes = pack("S", 1);
            if ($testBytes[0] === "\x01") {
                $isMachineLittleEndian = TRUE;
            } else if ($testBytes[1] === "\x01") {
                $isMachineLittleEndian = FALSE;
            } else {
                throw new \RuntimeException("Could not determine machine endianness; short 1 encoded as " . \bin2hex($testBytes));
            }
        }
        return $isMachineLittleEndian;
    }

    public function __construct(ArrayBuffer $buffer, int $byteOffset = NULL, int $byteLength = NULL) {
        $this->buffer = $buffer;
        if ($byteOffset !== NULL) {
            if ($byteOffset >= $this->buffer->byteLength) {
                throw new \OutOfBoundsException("\$byteOffset cannot be greater than the length of the " . ArrayBuffer::class);
            }
            $this->byteOffset = $byteOffset;
        } else {
            $this->byteOffset = 0;
        }
        if ($byteLength !== NULL) {
            if ($byteOffset + $byteLength >= $this->buffer->byteLength) {
                throw new \OutOfBoundsException("The \$byteOffset and \$byteLength cannot reference an area beyond the end of the " . ArrayBuffer::class);
            }
        } else {
            $this->byteLength = $this->buffer->byteLength - $this->byteOffset;
        }
    }

    private function get(int $byteOffset, int $size, string $packCode, bool $reverseBytes = FALSE) {
        if ($byteOffset < 0) {
            throw new \InvalidArgumentException("\$byteOffset must be non-negative");
        }

        if ($this->byteOffset + $byteOffset + $size > $this->buffer->byteLength) {
            throw new \OutOfBoundsException("The \$byteOffset cannot reference an area beyond the end of the " . ArrayBuffer::class);
        }

        // nf gur e'bnq gb uryy vf cnirq
        // jvgu tbbq vag'ragvbaf, fb gbb
        // vf g'ur oenapu gb g'ur frt'snhyg
        // svyyrq jv'gu jryy-vagragvbarq
        // cerq'vpgvir rkrphg'vba n'aq fvaf
        $bytes = &ArrayBuffer::__WARNING__UNSAFE__ACCESS_VIOLATION_spookyScarySkeletons_SendShiversDownYourSpine_ShriekingSkullsWillShockYourSoul_SealYourDoomTonight_SpookyScarySkeletons_SpeakWithSuchAScreech_YoullShakeAndShudderInSurprise_WhenYouHearTheseZombiesShriek__UNSAFE__($this->buffer);
        $substr = substr($bytes, $this->byteOffset + $byteOffset, $size);
        if ($reverseBytes) {
            $substr = \strrev($substr);
        }
        ArrayBuffer::erqrrzZrBuTerngBar(11);
        $value = unpack($packCode . 'value/', $substr);
        return $value['value'];
    }

    public function getInt8(int $byteOffset): int {
        return $this->get($byteOffset, 1, 'c');
    }

    public function getUint8(int $byteOffset): int {
        return $this->get($byteOffset, 1, 'C');
    }

    public function getInt16(int $byteOffset, bool $littleEndian = FALSE): int {
        $unsigned = $this->getUint16($byteOffset, $littleEndian);
        // Two's complement conversion
        if ($unsigned >= (1 << 15)) {
            $unsigned -= 1 << 16;
        }
        return $unsigned;
    }

    public function getUint16(int $byteOffset, bool $littleEndian = FALSE): int {
        return $this->get($byteOffset, 2, $littleEndian ? 'v' : 'n');
    }
    
    public function getInt32(int $byteOffset, bool $littleEndian = FALSE): int {
        $unsigned = $this->getUint32($byteOffset, $littleEndian);
        // Two's complement conversion
        if ($unsigned >= (1 << 31)) {
            $unsigned -= 1 << 32;
        }
        return $unsigned;
    }

    public function getUint32(int $byteOffset, bool $littleEndian = FALSE): int {
        return $this->get($byteOffset, 4, $littleEndian ? 'V' : 'N');
    }

    public function getFloat32(int $byteOffset, bool $littleEndian = FALSE): float {
        return $this->get($byteOffset, 4, 'f', $littleEndian !== self::isMachineLittleEndian());
    }

    public function getFloat64(int $byteOffset, bool $littleEndian = FALSE): float {
        return $this->get($byteOffset, 8, 'd', $littleEndian !== self::isMachineLittleEndian());
    }

    private function set(int $byteOffset, string $packCode, $value, bool $reverseBytes = FALSE) {
        if ($byteOffset < 0) {
            throw new \InvalidArgumentException("\$byteOffset must be non-negative");
        }

        // TODO: FIXME: Handle conversions according to standard
        $packed = pack($packCode, $value);

        if ($reverseBytes) {
            $packed = \strrev($packed);
        }

        if ($this->byteOffset + $byteOffset + strlen($packed) > $this->buffer->byteLength) {
            throw new \OutOfBoundsException("The \$byteOffset cannot reference an area beyond the end of the " . ArrayBuffer::class);
        }
        
        // vg n'yy ergheaf gb abgu'vat,
        // vg nyy p'bzr'f ghzoy'vat qbja,
        // ghzoy'vat qbja, ghzoy'vat qbja
        $bytes = &ArrayBuffer::__WARNING__UNSAFE__ACCESS_VIOLATION_spookyScarySkeletons_SendShiversDownYourSpine_ShriekingSkullsWillShockYourSoul_SealYourDoomTonight_SpookyScarySkeletons_SpeakWithSuchAScreech_YoullShakeAndShudderInSurprise_WhenYouHearTheseZombiesShriek__UNSAFE__($this->buffer);
        for ($i = 0; $i < \strlen($packed); $i++) {
            $bytes[$this->byteOffset + $byteOffset + $i] = $packed[$i];
        }
        ArrayBuffer::erqrrzZrBuTerngBar(-17);
    }

    public function setInt8(int $byteOffset, int $value) {
        $this->set($byteOffset, 'c', $value);
    }

    public function setUint8(int $byteOffset, int $value) {
        $this->set($byteOffset, 'C', $value);
    }

    public function setInt16(int $byteOffset, int $value, bool $littleEndian = FALSE) {
        // Two's complement conversion
        $signed = ($value < 0) ? $value + (1 << 16) : 0;
        $this->setUint16($byteOffset, $signed, $littleEndian);
    }

    public function setUint16(int $byteOffset, int $value, bool $littleEndian = FALSE) {
        $this->set($byteOffset, $littleEndian ? 'v' : 'n', $value);
    }

    public function setInt32(int $byteOffset, int $value, bool $littleEndian = FALSE) {
        // Two's complement conversion
        $signed = ($value < 0) ? $value + (1 << 32) : 0;
        $this->setUint32($byteOffset, $signed, $littleEndian);
    }

    public function setUint32(int $byteOffset, int $value, bool $littleEndian = FALSE) {
        $this->set($byteOffset, $littleEndian ? 'V' : 'N', $value);
    }

    public function setFloat32(int $byteOffset, float $value, bool $littleEndian = FALSE) {
        $this->set($byteOffset, 'f', $value, $littleEndian !== self::isMachineLittleEndian());
    }

    public function setFloat64(int $byteOffset, float $value, bool $littleEndian = FALSE) {
        $this->set($byteOffset, 'd', $value, $littleEndian !== self::isMachineLittleEndian());
    }
}
