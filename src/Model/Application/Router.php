<?php

declare(strict_types=1);

namespace App\Model\Application;

use App\Controller\AbstractController;
use App\Model\Application\Attribute\RouteAttribute;
use Exception;
use ReflectionAttribute;
use ReflectionClass;

final class Router
{
    public function __construct(
        public readonly App $app,
        public readonly string $baseDir
    ) {
        foreach ($this->getNonDotFiles($baseDir) as $file) {
            require_once $file;
        }
    }

    /**
     * @return array<string>
     */
    private function getNonDotFiles(string $dir): array
    {
        $ret = [];
        $subDirs = scandir($dir);
        if (!$subDirs) {
            return [];
        }
        foreach (array_filter(
            $subDirs,
            fn (string $str) => ! preg_match('/^\.+$/', $str)
        ) as $subDir) {
            $subDir = $dir . DIRECTORY_SEPARATOR . $subDir;
            if (str_ends_with($subDir, '.php')) {
                $ret[] = $subDir;
            } else {
                $ret = array_merge($ret, $this->getNonDotFiles($subDir));
            }
        }
        return $ret;
    }

    /**
     * Undocumented function
     *
     * @return array<ControllerWithRoute>
     */
    private function getAllControllersWithRoutes(): array
    {
        $returnValue = [];
        $allControllerClasses = array_filter(
            get_declared_classes(),
            fn (string $className) => is_subclass_of(
                $className,
                AbstractController::class
            )
        );

        foreach ($allControllerClasses as $controllerClass) {
            $reflectionClass = new ReflectionClass($controllerClass);
            $reflectionAttributes = $reflectionClass->getAttributes(
                RouteAttribute::class,
                ReflectionAttribute::IS_INSTANCEOF
            );
            foreach ($reflectionAttributes as $attribute) {
                $newAttribute = $attribute->newInstance();
                if ($newAttribute->debugOnly && ! $this->app->debug) {
                    continue;
                }
                /** @var AbstractController $newController */
                $newController = $reflectionClass->newInstance($this->app);
                $returnValue[] = new ControllerWithRoute(
                    $newController,
                    $newAttribute
                );
            }
        }
        usort(
            $returnValue,
            function (ControllerWithRoute $cwr1, ControllerWithRoute $cwr2) {
                $pathComp = strcmp(
                    $cwr1->routeAttribute->path,
                    $cwr2->routeAttribute->path
                );
                return $pathComp;
            }
        );
        return $returnValue;
    }

    // This is madness
    public function route(): App
    {
        $method = $this->app->request->method->value;

        foreach ($this->getAllControllersWithRoutes() as $controllerWithRoute) {
            $route = $controllerWithRoute->routeAttribute;
            $pathExists = $this->app->request->uri === $route->path;
            if ($pathExists) {
                if (in_array($method, $route->methods, true)) {
                    return $controllerWithRoute->controller->handleRequest();
                }
                $acceptedMethods = implode(', ', $route->methods);
                throw new Exception(
                    "This path does not support {$method} requests. Try one of {$acceptedMethods}.",
                    405
                );
            }
        }
        throw new Exception(
            "{$method} requests to {$this->app->request->uri} not supported",
            404
        );
    }
}
