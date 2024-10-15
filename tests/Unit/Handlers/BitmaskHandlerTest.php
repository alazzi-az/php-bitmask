<?php

use Alazziaz\Bitmask\Handlers\BitmaskHandler;

it('can be created with a valid mask', function () {
    $handler = BitmaskHandler::create(1);
    expect($handler->getValue())->toBe(1);
});

it('throws an exception for an invalid mask', function () {
    BitmaskHandler::create(-1);
})->throws(OutOfRangeException::class);

it('can add bits to the current mask', function () {
    $handler = BitmaskHandler::create(1);
    $handler->add(2); // 1 | 2 = 3
    expect($handler->getValue())->toBe(3);
});

it('can remove bits from the current mask', function () {
    $handler = BitmaskHandler::create(3); // 1 | 2
    $handler->remove(2); // 3 & ~2 = 1
    expect($handler->getValue())->toBe(1);
});

it('returns true when bits are present', function () {
    $handler = BitmaskHandler::create(3); // 1 | 2
    expect($handler->has(1))->toBeTrue();
    expect($handler->has(2))->toBeTrue();
});

it('returns false when bits are not present', function () {
    $handler = BitmaskHandler::create(1);
    expect($handler->has(2))->toBeFalse();
});

it('can get active bits from the current mask', function () {
    $handler = BitmaskHandler::create(5); // 1 | 4
    expect($handler->getActiveBits())->toEqual([1, 4]);
});

it('throws an exception when trying to add invalid bit values', function () {
    $handler = BitmaskHandler::create(1, 8);
    $handler->add(2);
    $handler->add(16);
})->throws(OutOfRangeException::class);

it('throws an exception when trying to remove invalid bit values', function () {
    $handler = BitmaskHandler::create(1, 4);
    $handler->remove(8);
})->throws(OutOfRangeException::class);
