<?php declare(strict_types=1);

namespace ajf\TypedArrays;

// https://www.khronos.org/registry/typedarray/specs/latest/#7.1
class Uint8ClampedArray extends TypedArray
{
    const BYTES_PER_ELEMENT = 1;
    const ELEMENT_PACK_CODE = 'C';

    public function offsetSet($offset, $value) {
        // TypedArray's offsetSet() will handle the type errors
        if (is_int($value) || is_float($value)) {
            $value = max(0, min($value, 255));
        }
        return parent::offsetSet($offset, $value);
    }
}
