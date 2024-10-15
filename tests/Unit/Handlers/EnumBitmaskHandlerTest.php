<?php

use Alazziaz\Bitmask\Contracts\Maskable;
use Alazziaz\Bitmask\Handlers\EnumBitmaskHandler;
use Alazziaz\Bitmask\Mappers\BitmaskMapper;

beforeAll(function () {
    enum TestUnitEnum: int
    {
        case BIT_ONE = 1;
        case BIT_TWO = 2;
    }

    enum TestInvalidEnum: int
    {
        case Bit_Four = 4;
    }
});


beforeEach(function () {

    $this->mockMaskHandler = $this->createMock(Maskable::class);
    $this->mockMaskMapper = new BitmaskMapper(TestUnitEnum::class);

    $this->enumBitmaskHandler = new EnumBitmaskHandler(
        TestUnitEnum::class,
        $this->mockMaskHandler,
        $this->mockMaskMapper
    );
});

it('validates enum in the constructor', function () {
    expect(fn() => new EnumBitmaskHandler(stdClass::class, $this->mockMaskHandler, $this->mockMaskMapper))
        ->toThrow(InvalidArgumentException::class, 'EnumBitmaskHandler enum must be a subclass of UnitEnum');
});

it('adds valid enum bits', function () {
    $this->mockMaskHandler
        ->expects($this->once())
        ->method('add')
        ->with(1, 2);

    $this->enumBitmaskHandler->add(TestUnitEnum::BIT_ONE, TestUnitEnum::BIT_TWO);
});

it('removes valid enum bits', function () {
    $this->mockMaskHandler
        ->expects($this->once())
        ->method('remove')
        ->with(1, 2);

    $this->enumBitmaskHandler->remove(TestUnitEnum::BIT_ONE, TestUnitEnum::BIT_TWO);
});

it('gets the value from mask handler', function () {
    $this->mockMaskHandler
        ->method('getValue')
        ->willReturn(3);

    $value = $this->enumBitmaskHandler->getValue();
    expect($value)->toBe(3);
});

it('gets active cases', function () {
    $this->mockMaskHandler
        ->method('getActiveBits')
        ->willReturn([1, 2]);

    expect($this->mockMaskMapper->toEnum(1))
        ->toBe(TestUnitEnum::BIT_ONE);

    $activeCases = $this->enumBitmaskHandler->getActiveCases();
    expect($activeCases)->toEqual([TestUnitEnum::BIT_ONE, TestUnitEnum::BIT_TWO]);
});

it('converts to array representation', function () {
    $this->mockMaskHandler
        ->method('has')
        ->willReturnOnConsecutiveCalls(true, false);

    $result = $this->enumBitmaskHandler->toArray();
    expect($result)->toEqual([
        'bit_one' => true,
        'bit_two' => false,
    ]);
});

it('validates enum bits in remove method', function () {
    expect(fn() => $this->enumBitmaskHandler->remove(TestInvalidEnum::Bit_Four))
        ->toThrow(InvalidArgumentException::class);
});

it('validates enum bits in add method', function () {

    expect(fn() => $this->enumBitmaskHandler->add(TestInvalidEnum::Bit_Four))
        ->toThrow(InvalidArgumentException::class);
});
