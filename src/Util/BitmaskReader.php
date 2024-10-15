<?php

namespace Alazziaz\Bitmask\Util;

class BitmaskReader
{
    public function getActiveBits(int $bitmask): array
    {
        $activeBits = [];
        $bitPosition = 1;

        while ($bitmask >= $bitPosition) {
            if ($bitmask & $bitPosition) {
                $activeBits[] = $bitPosition;
            }
            $bitPosition <<= 1;
        }

        return $activeBits;
    }

    public function getActiveIndexes(int $mask): array
    {
        $bitIndexes = [];
        foreach ($this->getActiveBits($mask) as $index => $bit) {
            $bitIndexes[$index] = $this->getMostSignificantBitIndex($bit);
        }

        return $bitIndexes;
    }

    public function countActiveBits(int $bitmask): int
    {

        return count($this->getActiveBits($bitmask));
    }

    public function getMostSignificantBitIndex(int $bitmask): int
    {
        return (int) log($bitmask, 2);
    }

    public function getLeastSignificantBitIndex(int $bitmask): int
    {
        return (int) log($bitmask & -$bitmask, 2);
    }

    public function convertToBinaryString(int $bitmask): string
    {
        return decbin($bitmask);
    }
}
