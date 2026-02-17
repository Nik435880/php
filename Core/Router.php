<?php

namespace Core;

use Core\Middleware;

class Router
{

    protected $routes = [];

    /**
     * Resolve callback so that [Controller::class, 'method'] is called on an instance.
     */
    protected function resolveCallback($callback): callable
    {
        if (is_array($callback) && is_string($callback[0])) {
            $class = $callback[0];
            $instance = Container::getInstance()->resolve($class) ?? new $class();
            return \Closure::fromCallable([$instance, $callback[1]]);
        }
        return $callback;
    }

    public function route($path, $method)
    {

        // Normalize path: strip query string and trailing slash (except for root)
        $path = strtok($path, '?') ?: $path;
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        if (!isset($this->routes[$method])) {
            throw new \Exception("Method not allowed");
        }

        // Exact match
        if (isset($this->routes[$method][$path])) {
            $route = $this->routes[$method][$path];

            // Run middleware (if any) before calling the route callback
            if (!Middleware::handle($route['middleware'] ?? null)) {
                // Middleware decided to stop further processing (e.g. via redirect)
                return;
            }

            $callback = $this->resolveCallback($route['callback']);
            return call_user_func($callback);
        }

        // Match dynamic routes (e.g. /users/{id})
        foreach ($this->routes[$method] as $routePath => $route) {
            if (strpos($routePath, '{') === false) {
                continue;
            }
            $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';
            if (preg_match($pattern, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                // Run middleware (if any) before calling the route callback
                if (!Middleware::handle($route['middleware'] ?? null)) {
                    return;
                }

                $callback = $this->resolveCallback($route['callback']);
                return call_user_func_array($callback, array_values($params));
            }
        }

        throw new \Exception("Route not found");
    }

    public function get($path, $callback, $middleware = null)
    {
        $this->routes['GET'][$path] = [
            'callback'   => $callback,
            'middleware' => $middleware,
        ];

        return $this;
    }

    public function post($path, $callback, $middleware = null)
    {
        $this->routes['POST'][$path] = [
            'callback'   => $callback,
            'middleware' => $middleware,
        ];

        return $this;
    }

    public function delete($path, $callback, $middleware = null)
    {
        $this->routes['DELETE'][$path] = [
            'callback'   => $callback,
            'middleware' => $middleware,
        ];

        return $this;
    }
}
