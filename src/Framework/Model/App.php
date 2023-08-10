<?php

declare(strict_types=1);

namespace App\Framework\Model;

use App\Framework\Exception\AppException;
use App\Framework\Model\Routing\Router;
use App\Framework\Model\Style\BaseStyles;
use App\Framework\Model\Style\Style;
use Exception;
use Stringable;

final class App
{
    /**
     * @var array<Style>
     */
    public readonly array $baseStyles;

    /**
     * @param array<Style> $style
     */
    public function __construct(
        public readonly Request $request,
        public readonly string $baseDir,
        public Router $router,
        public string $pageTitle = '',
        public string $view = '',
        public array $style = [],
        public bool $debug = true,
    ) {
        if (empty($pageTitle)) {
            $this->pageTitle = $request->uri;
        }
        $this->baseStyles = (new BaseStyles())->styles;
    }

    public static function createWithRequestFromGlobals(
        Router $router,
        ?string $baseDir = null
    ): self {
        return new self(
            Request::createFromGlobals(),
            $baseDir ?? $_SERVER['DOCUMENT_ROOT'],
            $router
        );
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
        $app = self::createWithRequestFromGlobals(new Router());
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

    public function route(): self
    {
        return $this->router->route($this);
    }

    public function printBaseStyles(): string
    {
        return $this->wrapInTags($this->baseStyles, 'style', [
            'lang' => 'css',
        ]);
    }

    public function printStyle(): string
    {
        return $this->wrapInTags($this->style, 'style', [
            'lang' => 'css',
        ]);
    }

    /**
     * @param string|Stringable|array<string|Stringable> $str
     * @param array<string, string> $tagAttrs
     */
    public function wrapInTags(
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
