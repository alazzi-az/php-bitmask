<?php

namespace Alazziaz\Bitmask;

use Alazziaz\Bitmask\Contracts\EnumMaskable;
use Alazziaz\Bitmask\Contracts\Maskable;
use Alazziaz\Bitmask\Handlers\BitmaskHandler;
use Alazziaz\Bitmask\Util\BitmaskConverter;
use Alazziaz\Bitmask\Util\BitmaskReader;
use Alazziaz\Bitmask\Validators\BitmaskValidator;
use UnitEnum;
class Bitmask {

    private static ?BitmaskConverter $converter = null;
    private static ?BitmaskReader $reader = null;

    private static ?BitmaskValidator $validator = null;
    /**
     * Lazy-load the BitmaskConverter instance.
     */
    private static function converter(): BitmaskConverter
    {
        if (self::$converter === null) {
            self::$converter = new BitmaskConverter();
        }
        return self::$converter;
    }

    /**
     * Lazy-load the BitmaskReader instance.
     */
    private static function reader(): BitmaskReader
    {
        if (self::$reader === null) {
            self::$reader = new BitmaskReader();
        }
        return self::$reader;
    }

    /**
     * Lazy-load the BitmaskValidator instance.
     */
    private static function validator(): BitmaskValidator
    {
        if (self::$validator === null) {
            self::$validator = new BitmaskValidator();
        }
        return self::$validator;
    }

    public static function bitmaskHandler(
        int $initialMask = 0,
        ?int $maxBit = null,
    ): Maskable {
        return  BitmaskHandler::create($initialMask, $maxBit);
    }

    public static function enumBitmaskHandler(
        string $enumClass,
        UnitEnum ...$bits
    ): EnumMaskable {

        return  self::enumBitmaskHandlerFactory()->create($enumClass,...$bits);
    }

    public static function enumBitmaskHandlerFactory(

    ): EnumBitmaskFactory {

        return  new EnumBitmaskFactory();
    }


    // Expose BitmaskConverter Methods

    public static function indexToBitMask(int $index): int
    {
        return self::converter()->indexToBitMask($index);
    }

    public static function bitMaskToIndex(int $mask): int
    {
        return self::converter()->bitMaskToIndex($mask);
    }

    public static function getEnumMaxBitValue(string $enum): int
    {
        return self::converter()->getEnumMaxBitValue($enum);
    }

    public static function bitMaskToArray(int $mask): array
    {
        return self::converter()->bitMaskToArray($mask);
    }

    public static function arrayToBitMask(array $bits): int
    {
        return self::converter()->arrayToBitMask($bits);
    }

    // Expose BitmaskReader Methods

    public static function getActiveBits(int $bitmask): array
    {
        return self::reader()->getActiveBits($bitmask);
    }
    public static function getActiveIndexes(int $bitmask): array
    {
        return self::reader()->getActiveIndexes($bitmask);
    }

    public static function countActiveBits(int $bitmask): int
    {
        return self::reader()->countActiveBits($bitmask);
    }

    public static function getMostSignificantBitIndex(int $bitmask): int
    {
        return self::reader()->getMostSignificantBitIndex($bitmask);
    }

    public static function getLeastSignificantBitIndex(int $bitmask): int
    {
        return self::reader()->getLeastSignificantBitIndex($bitmask);
    }

    public static function convertToBinaryString(int $bitmask): string
    {
        return self::reader()->convertToBinaryString($bitmask);
    }

    // Expose BitmaskValidator Methods

    public static function validateBit(int $bit): void
    {
        self::validator()->validateBit($bit);
    }

    public static function validateBits(array $bits): void
    {
        self::validator()->validateBits($bits);
    }


}
