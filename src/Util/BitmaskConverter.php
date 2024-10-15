<?php

namespace Alazziaz\Bitmask\Util;

use Alazziaz\Bitmask\Validators\BitmaskValidator;
use OutOfRangeException;
use BackedEnum;
use UnitEnum;

class BitmaskConverter
{

    public function indexToBitMask(int $index): int
    {
        if ($index < 0) {
            throw new OutOfRangeException("Index cannot be negative: {$index}");
        }
        return 1 << $index;
    }

    public function bitMaskToIndex(int $mask): int
    {
        (new BitmaskValidator())->ensureSingleBitIsSet($mask);
        return (new BitmaskReader())->getMostSignificantBitIndex($mask);
    }

    /** @param class-string<UnitEnum> $enum*/
    public  function getEnumMaxBitValue(string $enum): int
    {
        if ($this->isBackedEnum($enum)) {
            return max(array_map(fn($case) => $case->value, $enum::cases()));
        }

        return (1 << (count($enum::cases()) - 1)) - 1;
    }

    public function bitMaskToArray(int $mask): array
    {
        return (new BitmaskReader())->getActiveBits($mask);
    }

    public function arrayToBitMask(array $bits): int
    {
        (new BitmaskValidator())->validateBits($bits);

        $mask = 0;
        foreach ($bits as $bit) {
            $mask |=  $bit;
        }
        return $mask;
    }


    public function isBackedEnum(string $enum): bool
    {
        return is_subclass_of($enum, BackedEnum::class);
    }
}
