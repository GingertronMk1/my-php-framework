<?php

declare(strict_types=1);

namespace App\Framework\Model\Routing;

use App\Framework\Model\App;
use App\Framework\Model\RequestMethod;
use Exception;
use ReflectionClass;
use ReflectionMethod;

final readonly class Route
{
    /**
     * @param class-string $controllerClass
     */
    public function __construct(
        public string $path,
        public RequestMethod $requestMethod,
        public string $controllerClass,
        public string $methodName,
        public bool $debugOnly = false
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
        $reflectionClass = new ReflectionClass($this->controllerClass);
        $reflectedClass = $reflectionClass->newInstance($app);

        $reflectionMethod = new ReflectionMethod(
            $this->controllerClass,
            $this->methodName
        );
        $result = $reflectionMethod->invoke($reflectedClass);
        if (
            gettype($result) === 'object'
            && get_class($result) === App::class
        ) {
            return $result;
        }

        throw new Exception('Invalid class');
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
