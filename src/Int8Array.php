<?php declare(strict_types=1);

namespace ajf\TypedArrays;

// https://www.khronos.org/registry/typedarray/specs/latest/#7
class Int8Array extends TypedArray
{
    const BYTES_PER_ELEMENT = 1;
    const ELEMENT_PACK_CODE = 'c';
}
