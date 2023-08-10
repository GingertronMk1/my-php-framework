<?php

declare(strict_types=1);

namespace App\Framework\Model\Style;

use App\Framework\Exception\StyleException;
use App\Framework\Model\Html;
use Stringable;

final readonly class Style implements Stringable
{
    public const DIRECT_DESCENDANT_SELECTOR = '>';

    public const DIRECT_SIBLING_SELECTOR = '+';

    public const GENERAL_SIBLING_SELECTOR = '~';

    public const SELECTION_MODIFIERS = [
        self::DIRECT_DESCENDANT_SELECTOR,
        self::DIRECT_SIBLING_SELECTOR,
        self::GENERAL_SIBLING_SELECTOR,
    ];

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
        $newSelector = $this->selector;
        if (! empty($parentSelectors) && str_contains($newSelector, '&')) {
            $lastKey = array_key_last($parentSelectors);
            $parentSelector = $parentSelectors[$lastKey];
            // Replacing the first bit, so we keep any selection modifiers
            $newSelector = preg_replace(
                '/^&/',
                $parentSelector,
                $this->selector
            );

            if (null === $newSelector) {
                throw StyleException::nullSelector($parentSelector);
            }
            // Now replacing the rest, so get rid of them
            $parentSelector = str_replace(
                self::SELECTION_MODIFIERS,
                '',
                $parentSelector
            );
            $newSelector = preg_replace(
                '/&/',
                $parentSelector,
                $newSelector
            );
            if (null === $newSelector) {
                throw StyleException::nullSelector($parentSelector);
            }
            unset($parentSelectors[$lastKey]);
        }
        $parentSelectors[] = $newSelector;
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
        $properties = implode(PHP_EOL, $this->properties);
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

    public static function printInTags(self ...$styles): string
    {
        $styleArr = [];
        foreach ($styles as $style) {
            $styleArr[] = $style;
        }
        return Html::wrapInTags($styleArr, 'style', [
            'lang' => 'css',
        ]);
    }
}
