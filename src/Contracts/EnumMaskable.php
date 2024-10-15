<?php

namespace Alazziaz\Bitmask\Contracts;
use  UnitEnum;
interface EnumMaskable
{
    /**
     * Add the specified enum bits to the mask.
     */
    public function add(UnitEnum ...$bits): self;

    /**
     * Remove the specified enum bits from the mask.
     */
    public function remove(UnitEnum ...$bits): self;

    /**
     * Check if the mask contains all the specified enum bits.
     */
    public function has(UnitEnum ...$bits): bool;

    /**
     * Get the current mask value as an integer.
     */
    public function getValue(): int;

    /**
     * Get all active enum cases from the current mask.
     *
     * @return UnitEnum[]
     */
    public function getActiveCases(): array;

    /**
     * Convert the current mask to an associative array.
     *
     * @return array<string, bool> [enumKey => isActive]
     */
    public function toArray(): array;


}
