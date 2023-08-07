<?php

declare(strict_types=1);

namespace App\Framework\Controller;

use App\Framework\Model\App;

abstract class AbstractController
{
    public function __construct(
        public App $app
    ) {
    }

    public function handleRequest(): App
    {
        return $this->app;
    }

    /**
     * @param string|array<string> $str
     * @param array<string, string> $tagAttrs
     */
    public function wrapInTags(
        string|array $str,
        string $tag,
        array $tagAttrs = []
    ): string {
        if (is_array($str)) {
            $str = implode(PHP_EOL, $str);
        }
        $attrs = "";
        foreach ($tagAttrs as $attrName => $attrValue) {
            $attrs .= "\n{$attrName}=\"{$attrValue}\"";
        }
        return "<{$tag} {$attrs}>{$str}</{$tag}>";
    }
}
