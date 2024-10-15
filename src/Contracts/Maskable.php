<?php

namespace Alazziaz\Bitmask\Contracts;

interface Maskable
{
    /**
     * Add the specified bit values to the current mask.
     *
     * @return static
     */
    public function add(int ...$bitValues): self;

    /**
     * Remove the specified bit values from the current mask.
     *
     * @return static
     */
    public function remove(int ...$bitValues): self;

    /**
     * Check if the current mask contains all the specified bit values.
     */
    public function has(int ...$bitValues): bool;

    /**
     * Get the value of the current mask as an integer.
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
     */
    public function toString(): string;
}
