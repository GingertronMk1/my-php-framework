<?php

declare(strict_types=1);

namespace App\Framework\Model\Routing;

use App\Framework\Model\App;
use Exception;

final class Router
{
    /**
     * @param array<Route> $routes
     */
    public function __construct(
        public readonly App $app,
        public array $routes = []
    ) {
    }

    public static function create(App $app): self
    {
        return new self($app, []);
    }

    // This is madness
    public function route(): App
    {
        $appRequestMethod = $this->app->request->method;
        $appRequestURI = $this->app->request->uri;
        foreach ($this->routes as $route) {
            if ($route->path === $appRequestURI && $route->requestMethod === $appRequestMethod) {
                return $route->doRouting($this->app);
            }
        }

        throw new Exception('Doesn\'t exist');
    }

    public function addRoute(Route $route): self
    {
        if (
            ! empty(
                array_filter(
                    $this->routes,
                    fn (Route $currentRoute) => $currentRoute->controllerClass === $route->controllerClass && $currentRoute->methodName === $route->methodName
                ))) {
            throw new Exception(
                'Cannot use the same controller and method for multiple routes'
            );
        }
        $this->routes[] = $route;
        return $this;
    }

    public function addRoutes(Route ...$routes): self
    {
        foreach ($routes as $route) {
            $this->addRoute($route);
        }
        return $this;
    }
}
