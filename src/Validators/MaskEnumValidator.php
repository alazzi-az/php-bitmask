<?php

namespace Alazziaz\Bitmask\Validators;
use InvalidArgumentException;

final class MaskEnumValidator
{
    public static function validate(string $enum, array $bits): void
    {
        foreach ($bits as $bit) {
            if (!$bit instanceof $enum) {
                throw new InvalidArgumentException(sprintf(
                    'Expected %s enum, %s provided',
                    $enum,
                    $bit::class
                ));
            }
        }
    }
}
