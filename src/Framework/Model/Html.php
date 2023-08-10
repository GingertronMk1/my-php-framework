<?php

declare(strict_types=1);

namespace App\Framework\Model;

use Stringable;

final class Html
{
    /**
     * @param string|Stringable|array<string|Stringable> $str
     * @param array<string, string> $tagAttrs
     */
    public static function wrapInTags(
        string|Stringable|array $str,
        string $tag,
        array $tagAttrs = []
    ): string {
        if (is_array($str)) {
            $str = implode(PHP_EOL, $str);
        }
        $attrs = "";
        foreach ($tagAttrs as $attrName => $attrValue) {
            $attrs .= " {$attrName}=\"{$attrValue}\"";
        }
        return "<{$tag}{$attrs}>{$str}</{$tag}>";
    }
}
