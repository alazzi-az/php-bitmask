<?php

namespace Alazziaz\Bitmask\Contracts;

interface MaskableEnum
{
    public function toMaskKey(): string;
}
