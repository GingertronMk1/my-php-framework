<?php

declare(strict_types=1);

namespace App\Framework\Model\Style;

class Property
{
    public function __construct(
        public readonly string $property,
        public readonly string $value
    ) {
    }

    public function __toString(): string
    {
        return "\t{$this->property}: {$this->value};";
    }
}
