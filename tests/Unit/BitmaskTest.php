<?php

use Alazziaz\Bitmask\Bitmask;
use Alazziaz\Bitmask\Contracts\EnumMaskable;
use Alazziaz\Bitmask\Contracts\Maskable;

beforeEach(function () {});

it('can create a bitmask handler', function () {
    $maskable = Bitmask::bitmaskHandler(3);
    expect($maskable)->toBeInstanceOf(Maskable::class);
});

it('can create an enum bitmask handler', function () {
    enum SomeEnum: int
    {
        case FIRST = 1;
        case SECOND = 2;
        case THIRD = 4;
    }
    $enumClass = SomeEnum::class;
    $enumMaskable = Bitmask::enumBitmaskHandler($enumClass, SomeEnum::FIRST, SomeEnum::SECOND);
    expect($enumMaskable)->toBeInstanceOf(EnumMaskable::class);
});

it('can convert index to bitmask', function () {
    $result = Bitmask::indexToBitMask(3);
    expect($result)->toEqual(8); // 1 << 3
});

it('can convert bitmask to index', function () {
    $result = Bitmask::bitMaskToIndex(8); // 2^3
    expect($result)->toEqual(3);
});

it('throws an exception for invalid bitmask conversion', function () {
    Bitmask::bitMaskToIndex(0); // 0 has no set bits
})->throws(InvalidArgumentException::class);

it('can convert bitmask to array', function () {
    $result = Bitmask::bitMaskToArray(10); // Binary 1010
    expect($result)->toEqual([2, 8]); // Active bits are 2^1=2 and 2^3=8
});

it('can convert array to bitmask', function () {
    $result = Bitmask::arrayToBitMask([2, 8]);
    expect($result)->toEqual(10); // 2^1 + 2^3
});

it('can validate a single bit', function () {
    Bitmask::validateBit(2); // No exception should be thrown
})->throwsNoExceptions();

it('throws an exception when validating a non-single bit', function () {
    Bitmask::validateBit(3); // 3 is not a single bit
})->throws(InvalidArgumentException::class);

it('throws an exception for out-of-range bitmask', function () {
    Bitmask::validateBit(-1);
})->throws(OutOfRangeException::class);

it('can count active bits', function () {
    $result = Bitmask::countActiveBits(10); // Binary 1010
    expect($result)->toEqual(2); // Two active bits
});

it('can convert bitmask to binary string', function () {
    $result = Bitmask::convertToBinaryString(10); // Binary 1010
    expect($result)->toEqual('1010');
});
it('can retrieve active indexes from bitmask', function () {
    $result = Bitmask::getActiveIndexes(10);
    expect($result)->toEqual([1, 3]); // Active indexes are 2^1 and 2^3
});

it('can retrieve active bits from bitmask', function () {
    $result = Bitmask::getActiveBits(10); // Binary 1010
    expect($result)->toEqual([2, 8]); // Active bits are 2^1=2 and 2^3=8
});
