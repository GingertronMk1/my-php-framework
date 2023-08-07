<?php

declare(strict_types=1);

namespace App\Framework\Model\Style;

class Style
{
    /**
     * @param array<Property> $properties
     */
    private function __construct(
        public readonly string $selector,
        public readonly array $properties
    ) {
    }

    /**
     * @param array<string, string> $styles
     */
    public static function create(string $selector, array $styles): self
    {
        $modelProperties = [];
        foreach ($styles as $property => $value) {
            $modelProperties[] = new Property($property, $value);
        }
        return new self($selector, $modelProperties);
    }

    public function __toString(): string
    {
        $properties = implode(PHP_EOL, $this->properties);
        return "{$this->selector} { {$properties} }";
    }
}
