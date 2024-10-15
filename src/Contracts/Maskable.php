<?php

namespace Alazziaz\Bitmask\Contracts;

use UnitEnum;
interface Maskable
{
    /**
     * Add the specified bit values to the current mask.
     *
     * @param int ...$bitValues
     * @return static
     */
    public function add(int ...$bitValues): self;

    /**
     * Remove the specified bit values from the current mask.
     *
     * @param int ...$bitValues
     * @return static
     */
    public function remove(int ...$bitValues): self;

    /**
     * Check if the current mask contains all the specified bit values.
     *
     * @param int ...$bitValues
     * @return bool
     */
    public function has(int ...$bitValues): bool;

    /**
     * Get the value of the current mask as an integer.
     *
     * @return int
     */
    public function getValue(): int;

    /**
     * Get all active bits in the current mask.
     *
     * @return int[]
     */
    public function getActiveBits(): array;

    /**
     * Get the binary string representation of the current mask.
     *
     * @return string
     */
    public function toString(): string;
}
