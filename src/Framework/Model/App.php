<?php

declare(strict_types=1);

namespace App\Framework\Model;

use Exception;

final class App
{
    /**
     * @param array<string, string|array<string>> $style
     */
    public function __construct(
        public readonly Request $request,
        public readonly string $baseDir,
        public string $pageTitle = '',
        public string $view = '',
        public array $style = [],
        public bool $debug = true,
    ) {
        if (empty($pageTitle)) {
            $this->pageTitle = $request->uri;
        }
    }

    public static function createWithRequestFromGlobals(
        ?string $baseDir = null
    ): self {
        return new self(
            Request::createFromGlobals(),
            $baseDir ?? $_SERVER['DOCUMENT_ROOT']
        );
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

    public function getDataFileContents(
        string $fileName,
        bool $isJson = false
    ): mixed
    {
        $fullFilePath = "{$this->baseDir}/data/{$fileName}";
        if (! file_exists($fullFilePath)) {
            throw new \Exception("File {$fullFilePath} does not exist");
        }
        $fileContents = file_get_contents($fullFilePath);
        if ($isJson) {
            if (!is_string($fileContents)) {
                throw new Exception("Invalid JSON in file `{$fullFilePath}`");
            }
            return json_decode($fileContents, true);
        }
        return $fileContents;
    }

    public static function fromException(Exception $e): self
    {
        $app = self::createWithRequestFromGlobals();
        $app->view = '<pre>' . print_r($e, true) . '</pre>';
        return $app;
    }
}
