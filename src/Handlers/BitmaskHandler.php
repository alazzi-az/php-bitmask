<?php

namespace Alazziaz\Bitmask\Handlers;

use Alazziaz\Bitmask\Contracts\Maskable;
use Alazziaz\Bitmask\Util\BitmaskReader;
use Alazziaz\Bitmask\Validators\BitmaskValidator;

final class BitmaskHandler implements Maskable
{
    private BitmaskValidator $validator;

    private BitmaskReader $bitmaskReader;

    public function __construct(
        private int $currentMask = 0,
        private readonly ?int $maxBit = null,
        bool $isIntBacked = false,
    ) {
        $this->validator = new BitmaskValidator($this->maxBit, $isIntBacked);
        $this->bitmaskReader = new BitmaskReader;
        $this->validator->validateMask($this->currentMask);
    }

    public static function create(int $currentMask = 0, ?int $maxBit = null): self
    {
        return new self($currentMask, $maxBit, true);
    }

    public function __toString(): string
    {
        return (string) $this->currentMask;
    }

    public function toString(): string
    {
        return $this->bitmaskReader->convertToBinaryString($this->currentMask);
    }

    public function getValue(): int
    {
        return $this->currentMask;
    }

    public function add(int ...$bitValues): self
    {

        $this->validateBitValues($bitValues);
        foreach ($bitValues as $bitValue) {
            $this->currentMask |= $bitValue;
        }

        return $this;
    }

    public function remove(int ...$bitValues): self
    {
        foreach ($bitValues as $bitValue) {
            if ($this->has($bitValue)) {
                $this->currentMask &= ~$bitValue;
            }
        }

        return $this;
    }

    public function has(int ...$bitValues): bool
    {
        $this->validateBitValues($bitValues);
        foreach ($bitValues as $bitValue) {
            if (($this->currentMask & $bitValue) !== $bitValue) {
                return false;
            }
        }

        return true;
    }

    public function getActiveBits(): array
    {
        return $this->bitmaskReader->getActiveBits($this->getValue());
    }

    private function validateBitValues(array $bitValues): void
    {
        $this->validator->validateBits($bitValues);
    }
}
