<?php

declare(strict_types=1);

namespace App\Model\Application\Routing;

use App\Model\Application\App;
use App\Model\Application\RequestMethod;
use Exception;
use ReflectionClass;

final class Route
{
    public function __construct(
        public readonly string $path,
        public readonly RequestMethod $requestMethod,
        public readonly string $controllerClass,
        public readonly string $methodName
    ) {
    }

    public static function create(
        string $path,
        string $requestMethod,
        string $controllerClass,
        string $methodName
    ): self {
        $requestMethodObject = RequestMethod::getFromString($requestMethod);
        if (! class_exists($controllerClass)) {
            throw new Exception("{$controllerClass} is not a valid class.");
        }
        if (! method_exists($controllerClass, $methodName)) {
            throw new Exception("{$controllerClass}::{$methodName} is not a valid method.");
        }

        return new self($path, $requestMethodObject, $controllerClass, $methodName);
    }

    public function doRouting(App $app): App
    {
        $reflection = new ReflectionClass($this->controllerClass);
        $actualController = $reflection->newInstance($app);
        return call_user_func([$actualController, $this->methodName]);
    }
}
