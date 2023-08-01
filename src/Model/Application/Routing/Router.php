<?php

declare(strict_types=1);

namespace App\Model\Application\Routing;

use App\Model\Application\App;
use Exception;

final class Router
{
    /**
     * @param array<Route> $routes
     */
    public function __construct(
        public readonly App $app,
        public readonly string $baseDir,
        public array $routes = []
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
        if (! $subDirs) {
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
            throw new Exception('Cannot use the same controller and method for multiple routes');
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
