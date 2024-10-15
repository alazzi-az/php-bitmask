<?php

namespace Alazziaz\Bitmask\Tests\Unit\Mappers;

use Alazziaz\Bitmask\Mappers\BitmaskMapper;
use InvalidArgumentException;

beforeAll(function () {
    enum SimpleEnum: int
    {
        case FIRST = 1;
        case SECOND = 2;
        case THIRD = 4;
    }
});
beforeEach(function () {

    $this->mapper = new BitmaskMapper(SimpleEnum::class);
});

it('initializes mappings correctly', function () {
    $expectedMappings = [
        'FIRST' => 1,   // Assuming the index is directly assigned to the bit value
        'SECOND' => 2,
        'THIRD' => 4,
    ];
    expect($this->mapper->getAllBitMasks())->toEqual($expectedMappings);
});

it('retrieves a bitmask for a valid flag name', function () {
    expect($this->mapper->getBitMask('FIRST'))->toEqual(1);
    expect($this->mapper->getBitMask('SECOND'))->toEqual(2);
    expect($this->mapper->getBitMask('THIRD'))->toEqual(4);
});

it('throws an exception for an invalid flag name', function () {
    $this->mapper->getBitMask('INVALID'); // This should throw an InvalidArgumentException
})->throws(InvalidArgumentException::class, 'Invalid flag name: INVALID');

it('converts enum case to bit', function () {
    expect($this->mapper->toBit(SimpleEnum::FIRST))->toEqual(1);
    expect($this->mapper->toBit(SimpleEnum::SECOND))->toEqual(2);
    expect($this->mapper->toBit(SimpleEnum::THIRD))->toEqual(4);
});

it('throws an exception for invalid enum case', function () {
    enum InvalidEnum: int
    {
        case Test = 8;
    }
    $this->mapper->toBit(InvalidEnum::Test);
})->throws(InvalidArgumentException::class, 'Invalid enum case:');

it('resolves a bit value from an enum case', function () {
    expect($this->mapper->resolveBitValue(SimpleEnum::FIRST, 0))->toEqual(1);
    expect($this->mapper->resolveBitValue(SimpleEnum::SECOND, 1))->toEqual(2);
    expect($this->mapper->resolveBitValue(SimpleEnum::THIRD, 2))->toEqual(4);
});

it('converts bit to enum case', function () {
    expect($this->mapper->toEnum(1))->toEqual(SimpleEnum::FIRST);
    expect($this->mapper->toEnum(2))->toEqual(SimpleEnum::SECOND);
    expect($this->mapper->toEnum(4))->toEqual(SimpleEnum::THIRD);
});

it('throws an exception if no matching enum is found for a bit value', function () {
    $this->mapper->toEnum(8); // Should throw an InvalidArgumentException
})->throws(InvalidArgumentException::class, 'No matching enum found for bit value: 8');
