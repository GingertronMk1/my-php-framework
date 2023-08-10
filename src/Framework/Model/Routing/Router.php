<?php

declare(strict_types=1);

namespace App\Framework\Model\Routing;

use App\Framework\Exception\RouterException;
use App\Framework\Model\App;
use Exception;

final class Router
{
    /**
     * @var array<Route>
     */
    public array $routes = [];

    /**
     * @param array<Route> $routes
     */
    public function __construct(array $routes = [])
    {
        $this->addRoutes(...$routes);
    }

    // This is madness
    public function route(App $app): App
    {
        $appRequestMethod = $app->request->method;
        $appRequestURI = $app->request->uri;
        foreach ($this->routes as $route) {
            if ($route->path === $appRequestURI && $route->requestMethod === $appRequestMethod) {
                return $route->doRouting($app);
            }
        }

        throw new Exception('Doesn\'t exist');
    }

    public function addRoute(Route $route): self
    {
        foreach ($this->routes as $currentRoute) {
            if (
                $route->path === $currentRoute->path &&
                $route->requestMethod === $currentRoute->requestMethod
            ) {
                throw RouterException::pathAndMethodTaken(
                    $route->path,
                    $route->requestMethod
                );
            }
            if ($route->name !== '' && $route->name === $currentRoute->name) {
                throw RouterException::nameTaken($route->name);
            }
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

    public function getRouteFromName(string $name): Route
    {
        foreach ($this->routes as $route) {
            if ($route->name === $name) {
                return $route;
            }
        }

        throw RouterException::noRouteFoundWithName($name);
    }
}
