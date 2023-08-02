<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Framework\App;

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

    public function wrapInTags(
        string $str,
        string $tag,
        array $tagAttrs = []
    ): string {
        $implodedAttrs = ' ' . implode(' ', $tagAttrs);
        return "<{$tag}{$implodedAttrs}>{$str}</{$tag}>";
    }
}
