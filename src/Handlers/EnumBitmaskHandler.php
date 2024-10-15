<?php

namespace Alazziaz\Bitmask\Handlers;

use Alazziaz\Bitmask\Contracts\EnumMaskable;
use Alazziaz\Bitmask\Contracts\Maskable;
use Alazziaz\Bitmask\Contracts\MaskableEnum;
use Alazziaz\Bitmask\Mappers\BitmaskMapper;
use Alazziaz\Bitmask\Validators\EnumValidator;
use Alazziaz\Bitmask\Validators\MaskEnumValidator;
use UnitEnum;

final class EnumBitmaskHandler implements EnumMaskable
{
    public function __construct(
        /** @var UnitEnum */
        private readonly string $enum,
        private readonly Maskable $maskHandler,
        private readonly BitmaskMapper $maskMapper

    ) {

        EnumValidator::validate($this->enum);
    }

    public function remove(UnitEnum ...$bits): self
    {
        MaskEnumValidator::validate($this->enum, $bits);
        $this->maskHandler->remove(...$this->enumToInt(...$bits));

        return $this;
    }

    private function enumToInt(UnitEnum ...$bits): array
    {
        return array_map(fn (UnitEnum $bit) => $this->maskMapper->getBitMask($bit->name), $bits);
    }

    public function add(UnitEnum ...$bits): self
    {
        MaskEnumValidator::validate($this->enum, $bits);

        $this->maskHandler->add(...$this->enumToInt(...$bits));

        return $this;
    }

    public function getValue(): int
    {
        return $this->maskHandler->getValue();
    }

    public function getActiveCases(): array
    {
        $activeBits = $this->maskHandler->getActiveBits();
        $cases = [];

        foreach ($activeBits as $bitValue) {
            $cases[] = $this->maskMapper->toEnum($bitValue);
        }

        return $cases;
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->enum::cases() as $mask) {
            if ($mask instanceof UnitEnum) {
                $key = $mask instanceof MaskableEnum ? $mask->toMaskKey() : strtolower($mask->name);
                $result[$key] = $this->has($mask);
            }
        }

        return $result;
    }

    public function has(UnitEnum ...$bits): bool
    {
        MaskEnumValidator::validate($this->enum, $bits);

        return $this->maskHandler->has(...$this->enumToInt(...$bits));
    }
}
