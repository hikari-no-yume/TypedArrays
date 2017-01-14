Typed Arrays for PHP
====================

[`ajf/typed-arrays`](https://packagist.org/packages/ajf/typed-arrays) is a Composer package which implements [ECMAScript Typed Arrays](http://www.ecma-international.org/ecma-262/6.0/#sec-typedarray-objects) (previously a Khronos Group standard) for PHP 7.

Why would you want this? For reduced memory usage, of course!

What's supported
----------------

* [`ArrayBuffer`](https://www.khronos.org/registry/typedarray/specs/latest/#5)
* [Typed array views](https://www.khronos.org/registry/typedarray/specs/latest/#7)
  * [`Uint8ClampedArray`](https://www.khronos.org/registry/typedarray/specs/latest/#7.1)
* [`DataView`](https://www.khronos.org/registry/typedarray/specs/latest/#8)

What's not (yet) supported
--------------------------

* [Correct type conversion rules](https://www.khronos.org/registry/typedarray/specs/latest/#3)
* [`Transferable`](https://www.khronos.org/registry/typedarray/specs/latest/#9) (useless in the context of PHP)
* 32-bit platforms
* PHP 5.x

What really needs to be done and hasn't been
--------------------------------------------

* Remaining `TypedArray` tests
* Writing `DataView` tests
* Release version

Usage
-----

###End-user

Require `ajf/typed-arrays` in composer. Note that only `dev-master` exists for now.

###Developer

Tests can be run with `phpunit`.
