<?php

namespace Alazziaz\Bitmask\Validators;



use Alazziaz\Bitmask\Util\BitmaskConverter;
use Alazziaz\Bitmask\Util\BitmaskReader;
use InvalidArgumentException;
use OutOfRangeException;

 class BitmaskValidator
{
    public function __construct(
        private  ?int $maxBit = null,
        private  bool $isIntBacked = false
    )
    {

    }
    public function setMaxBit(int $maxBit ):self{
        $this->maxBit=$maxBit;
        return $this;
    }
     public function setIsIntBackedEnum(bool $isIntBacked = false):self{
         $this->isIntBacked=$isIntBacked;
         return $this;
     }
    public function validateBit(int $bit): void
    {
        $this->validateMask($bit);
        if (!$this->isOnlyOneBitSet($bit)) {
            throw new InvalidArgumentException("Provided value {$bit} is not a single bit.");
        }
    }
    public function validateBits(array $bits): void
    {
        foreach ($bits as $bit) {
            $this->validateBit($bit);
        }
    }
    public function validateMask(int $mask): void
    {

        if ($mask < 0 || $this->isOutOfRange($mask)) {
            throw new OutOfRangeException("Mask value {$mask} is out of range.");
        }
    }

    public function isOutOfRange(int $mask): bool
    {
        if ($this->maxBit === null) {
            return false;
        }


        $maxValue = $this->isIntBacked
            ? $this->maxBit
            : (new BitmaskConverter())->indexToBitMask($this->maxBit + 1);


        return $mask >= $maxValue;
    }

    public function isOnlyOneBitSet(int $mask): bool
    {
        return (1 << (new BitmaskReader())->getMostSignificantBitIndex($mask)) === $mask;
    }

    public function ensureSingleBitIsSet(int $bitmask): void
    {
        if (!$this->isOnlyOneBitSet($bitmask)) {
            throw new InvalidArgumentException('The provided argument must represent a single set bit.');
        }
    }
}
