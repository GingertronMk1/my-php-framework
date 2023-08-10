<?php

declare(strict_types=1);

namespace App\Framework\Model\Style;

use Stringable;

class Property implements Stringable
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
