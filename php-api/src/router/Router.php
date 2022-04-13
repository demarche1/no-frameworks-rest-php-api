<?php
namespace App\router;

use \App\http\Response;

class Router
{
    private $uri;
    private $requestMethod;
    private $routes;

    public function __construct($routes = [])
    {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->routes = $routes[$this->requestMethod];
    }

    private function getExactRoutes($uri, $routes)
    {
        return array_key_exists($uri, $routes) ? [$uri => $routes[$uri]] : [];
    }

    public function getDynamicsRoutes($uri, $routes)
    {
        return array_filter($routes, function ($value) use ($uri) {
            $regex = str_replace('/', '\/', ltrim($value, '/'));
            return preg_match("/^$regex$/", ltrim($uri, '/'));
        }, ARRAY_FILTER_USE_KEY);
    }

    private function getParams($uri, $matchedUri)
    {
        if (empty($matchedUri)) {
            return [];
        }

        $patternUri = array_keys($matchedUri)[0];

        return array_diff($uri, explode('/', ltrim($patternUri, '/')));
    }

    private function formatParams($uri, $params)
    {
        $paramsData = [];

        foreach ($params as $index => $param) {
            $paramsData[$uri[$index - 1]] = $param;
        }

        return $paramsData;
    }

    public function init()
    {
        $matchedUri = $this->getExactRoutes($this->uri, $this->routes);
        $params = [];

        if (empty($matchedUri)) {
            $matchedUri = $this->getDynamicsRoutes($this->uri, $this->routes);
            $uriExploded = explode('/', ltrim($this->uri, '/'));
            $params = $this->getParams($uriExploded, $this->matchedUri);
            $params = $this->formatParams($uriExploded, $params);
        }

        if (!empty($matchedUri)) {
            return controller($matchedUri, $params);
        }

        Response::send(400, [
            'status' => 'Bad Request',
        ]);
        exit;
    }
}