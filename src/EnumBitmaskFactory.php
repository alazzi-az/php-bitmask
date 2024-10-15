<?php

namespace Alazziaz\Bitmask;

use Alazziaz\Bitmask\Handlers\BitmaskHandler;
use Alazziaz\Bitmask\Handlers\EnumBitmaskHandler;
use Alazziaz\Bitmask\Mappers\BitmaskMapper;
use Alazziaz\Bitmask\Util\BitmaskConverter;
use UnitEnum;

class EnumBitmaskFactory
{
    public function __construct(
        private readonly BitmaskConverter $converter = new BitmaskConverter
    ) {}

    /**
     * @param  class-string<UnitEnum>  $enum
     */
    public function create(string $enum, UnitEnum ...$bits): EnumBitmaskHandler
    {
        $currentMask = array_reduce(
            $bits,
            fn ($mask, $bit) => $mask | $bit->value,
            0
        );

        return $this->createWithMask($enum, $currentMask);
    }

    /**
     * @param  class-string<UnitEnum>  $enum
     */
    public function createWithMask(string $enum, int $mask): EnumBitmaskHandler
    {
        $maskHandler = new BitmaskHandler($mask, isIntBacked: $this->converter->isBackedEnum($enum));
        $maskMapper = new BitmaskMapper($enum);

        return new EnumBitmaskHandler($enum, $maskHandler, $maskMapper);
    }

    /**
     * Create an empty mask for the provided enum.
     */
    public function createNone(string $enum): EnumBitmaskHandler
    {
        return $this->createWithMask($enum, 0);
    }

    /**
     * Create a handler with all possible enum cases activated.
     */
    public function createAll(string $enum): EnumBitmaskHandler
    {
        return $this->create($enum, ...$enum::cases());
    }

    public function without(string $enum, UnitEnum ...$bits): EnumBitmaskHandler
    {
        return $this->createAll($enum)->remove(...$bits);
    }

    public function none(string $enum): EnumBitmaskHandler
    {
        return $this->create($enum);
    }
}
