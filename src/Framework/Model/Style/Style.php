<?php

declare(strict_types=1);

namespace App\Framework\Model\Style;

final readonly class Style
{
    /**
     * @param array<Property> $properties
     * @param array<self> $subStyles
     */
    private function __construct(
        public string $selector,
        public array $properties,
        public array $subStyles = []
    ) {
    }

    /**
     * @param array<string, string> $styles
     */
    public static function create(
        string $selector,
        array $styles,
        self ...$subStyles
    ): self {
        $modelProperties = [];
        foreach ($styles as $property => $value) {
            $modelProperties[] = new Property($property, $value);
        }

        $newSubStyles = [];
        foreach ($subStyles as $subStyle) {
            $newSubStyles[] = $subStyle;
        }
        return new self($selector, $modelProperties, $newSubStyles);
    }

    public function normaliseChildren(): self
    {
        return $this->prependParentSelector();
    }

    /**
     * @param array<string> $parentSelectors
     */
    private function prependParentSelector(
        array $parentSelectors = []
    ): self {
        $parentSelectors[] = $this->selector;
        return new self(
            implode(' ', $parentSelectors),
            $this->properties,
            array_map(
                fn (self $subStyle) => $subStyle->prependParentSelector(
                    $parentSelectors
                ),
                $this->subStyles,
            )
        );
    }

    public function __toString(): string
    {
        $properties = implode(
            PHP_EOL,
            $this->properties
        );
        return "{$this->selector} {\n{$properties}\n}\n\n"
                . implode(
                    PHP_EOL,
                    array_map(
                        fn (self $subStyle) => (string) $subStyle,
                        $this->subStyles
                    )
                );
    }

    public static function createAndPrint(string ...$args): void
    {
        $self = self::create(...$args);
        echo $self;
        echo '/* ' . print_r($self, true) . ' */' . PHP_EOL . PHP_EOL;
    }
}
