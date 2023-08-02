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
        public readonly string $methodName,
        public readonly bool $debugOnly = false
    ) {
    }

    public static function create(
        string $path,
        RequestMethod $requestMethod,
        string $controllerClass,
        string $methodName,
        bool $debugOnly = false
    ): self {
        if (! class_exists($controllerClass)) {
            throw new Exception("{$controllerClass} is not a valid class.");
        }
        if (! method_exists($controllerClass, $methodName)) {
            throw new Exception(
                "{$controllerClass}::{$methodName} is not a valid method."
            );
        }

        return new self(
            $path,
            $requestMethod,
            $controllerClass,
            $methodName,
            $debugOnly
        );
    }

    public function doRouting(App $app): App
    {
        $reflection = new ReflectionClass($this->controllerClass);
        $actualController = $reflection->newInstance($app);
        return call_user_func([$actualController, $this->methodName]);
    }

    public static function get(
        string $path,
        string $controllerClass,
        string $methodName,
        bool $debugOnly = false
    ): self {
        return self::create(
            $path,
            RequestMethod::GET,
            $controllerClass,
            $methodName,
            $debugOnly
        );
    }

    public static function post(
        string $path,
        string $controllerClass,
        string $methodName,
        bool $debugOnly = false
    ): self {
        return self::create(
            $path,
            RequestMethod::POST,
            $controllerClass,
            $methodName,
            $debugOnly
        );
    }
}
