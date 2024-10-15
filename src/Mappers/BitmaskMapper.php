<?php

namespace Alazziaz\Bitmask\Mappers;


use Alazziaz\Bitmask\Util\BitmaskConverter;
use InvalidArgumentException;
use UnitEnum;
use BackedEnum;

/** @property UnitEnum $enumClass */
class BitmaskMapper
{
    private array $flagMappings = [];
    private BitmaskConverter $converter;
    /** @var $enumClass class-string<UnitEnum> */

    public function __construct(

        private readonly string $enumClass
    )
    {
        $this->converter = new BitmaskConverter();
        $this->initializeMappings($enumClass);
    }

    public function getBitMask(string $flagName): int
    {
        return $this->flagMappings[$flagName] ?? throw new InvalidArgumentException("Invalid flag name: $flagName");
    }

    public function getAllBitMasks(): array
    {
        return $this->flagMappings;
    }

    public  function resolveBitValue(UnitEnum $case,int $index): int
    {
        return $case instanceof BackedEnum ? (int)$case->value : $this->converter->indexToBitMask($index);
    }
    public function toEnum(int $bit): UnitEnum
    {

        foreach ($this->enumClass::cases() as $case) {
            if ($this->toBit($case) === $bit) {
                return $case;
            }
        }
        throw new InvalidArgumentException("No matching enum found for bit value: $bit");
    }
    public function toBit(UnitEnum $case): int
    {
        $this->ensureValidEnum($case);
        return $this->flagMappings[$case->name];
    }
    /**
     * @param class-string<UnitEnum> $enum
     */
    public function initializeMappings(string $enum): void
    {
        foreach ($enum::cases() as $index => $case) {
            $this->flagMappings[$case->name] = $this->resolveBitValue($case, $index);
        }
    }

    private function ensureValidEnum(UnitEnum $case): void
    {
        if (!$case instanceof $this->enumClass) {
            throw new InvalidArgumentException(
                "Invalid enum case: " . get_class($case) . " does not belong to {$this->enumClass}"
            );
        }
    }
}
