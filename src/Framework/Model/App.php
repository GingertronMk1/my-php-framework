<?php

declare(strict_types=1);

namespace App\Framework\Model;

use App\Framework\Exception\AppException;
use App\Framework\Model\Style\Style;
use Exception;

final class App
{
    /**
     * @param array<Style> $style
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
        foreach ($this->style as $style) {
            $ret .= $style . PHP_EOL;
        }
        return $ret;
    }

    public function getDataFileContents(
        string $fileName,
        bool $isJson = false
    ): mixed {
        $fullFilePath = "{$this->baseDir}/data/{$fileName}";
        if (! file_exists($fullFilePath)) {
            throw AppException::fileDoesNotExist($fullFilePath);
        }
        $fileContents = file_get_contents($fullFilePath);
        if ($isJson) {
            if (! is_string($fileContents)) {
                throw AppException::invalidJSON($fullFilePath);
            }
            return json_decode($fileContents, true);
        }
        return $fileContents;
    }

    /**
     * @param array<string, mixed> $variables
     */
    private function getView(string $viewName, array $variables): string
    {
        extract($variables);
        $viewsDir = $this->baseDir;
        ob_start();
        include("{$viewsDir}/views/{$viewName}");
        $var = ob_get_clean();
        if ($var === false) {
            return "There was an error rendering {$viewName}";
        }
        return $var;
    }

    public static function fromException(Exception $e): self
    {
        $app = self::createWithRequestFromGlobals();
        $app->view = $app->getView('framework/exception.php', [
            'exception' => $e,
        ]);
        $app->setStyle(
            Style::create('td>pre', [
                'overflow-y' => 'scroll',
                'max-height' => '200px',
            ]),
        );
        return $app;
    }

    public function addStyles(Style ...$styles): self
    {
        foreach ($styles as $style) {
            $this->style[] = $style;
        }
        return $this;
    }

    public function setStyle(Style ...$styles): self
    {
        $this->style = [];
        return $this->addStyles(...$styles);
    }
}
