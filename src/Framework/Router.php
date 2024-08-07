<?php

declare(strict_types=1);

namespace Framework;

class Router
{
    private array $routes = [];
    private array $middlewares = [];

    public function add(string $method, string $path, array $controller)
    {
        $path = $this->normalizePath($path);

        $regexPath = preg_replace('#{[^/]+}#', '([^/]+)', $path);

        $this->routes[] = [
            'path' => $path,
            'method' => strtoupper($method),
            'controller' => $controller,
            'middlewares' => [],
            'regexPath' => $regexPath
        ];
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path, "/");
        $path = "/{$path}/";
        $path = preg_replace('#[/]{2,}#', '/', $path);

        return $path;
    }

    public function dispatch(string $path, string $method, Container $container = null)
    {
        $path = $this->normalizePath($path);
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if (!preg_match("#^{$route['regexPath']}$#", $path, $paramValues) || $route['method'] !== $method) {
                continue;
            }

            array_shift($paramValues);  // $paramValues nous renvoie d'abord l'element entier correspondant à la correspondance. Ensuite les valeurs capturés

            preg_match_all('#{([^/]+)}#', $route['path'], $paramKeys);  //Récupère la valeur présente entre {}. Le nom du paramètre.

            $paramKeys = $paramKeys[1];     // preg_match_all nous renvoie une liste contenant d'abord une liste des elements complet qui correspond, puis ensuite une liste des noms des elements capturés.

            $params = array_combine($paramKeys, $paramValues);

            [$class, $function] = $route['controller'];

            $controllerInstance = $container ? $container->resolve($class) : new $class;

            $action = fn () => $controllerInstance->{$function}($params);

            $allMiddleware = [...$route['middlewares'], ...$this->middlewares];    // On met bien nos 'global middlewares' après ceux specifique à une route. Il faut initialiser la session avant tout.

            foreach ($allMiddleware as $middleware) {
                $middlewareInstance = $container ? $container->resolve($middleware) : new $middleware;
                $action = fn () => $middlewareInstance->process($action);
            }

            $action();

            return;
        }
    }

    public function addMiddleware(string $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function addRouteMiddleware(string $middleware)
    {
        $lastRouteKey = array_key_last($this->routes);
        $this->routes[$lastRouteKey]['middlewares'][] = $middleware;
    }
}
