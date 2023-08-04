<?php

declare(strict_types=1);

namespace App\Framework\Model;

final class App
{
    /**
     * @param array<string, string> $style
     */
    public function __construct(
        public readonly Request $request,
        public string $pageTitle = '',
        public string $view = '',
        public array $style = [],
        public bool $debug = true
    ) {
        if (empty($pageTitle)) {
            $this->pageTitle = $request->uri;
        }
    }

    public static function createWithRequestFromGlobals(): self
    {
        return new self(Request::createFromGlobals());
    }

    public function renderStyle(): string
    {
        $ret = '';
        foreach ($this->style as $selector => $style) {
            if (is_array($style)) {
                $style = implode(';', $style);
            }
            $ret .= "{$selector} { {$style} }" . PHP_EOL;
        }
        return $ret;
    }
}

