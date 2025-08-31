<?php

declare(strict_types=1);

namespace Http;

use function Core\pipe;
use function Http\createRequest;
use function Http\createResponse;
use function Http\sendResponse;
use function Http\getMethod;
use function Http\getUri;

/**
 * Creates an empty router configuration.
 *
 * @return array The initial routes array.
 */
function createRouter(): array
{
    return [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];
}

/**
 * Registers a GET route.
 *
 * @param array $routes The current routes array.
 * @param string $uri The URI to match.
 * @param callable $handler The handler function for the route.
 * @return array The new routes array.
 */
function get(array $routes, string $uri, callable $handler): array
{
    $newRoutes = $routes;
    $newRoutes['GET'][$uri] = $handler;
    return $newRoutes;
}

/**
 * Registers a POST route.
 *
 * @param array $routes The current routes array.
 * @param string $uri The URI to match.
 * @param callable $handler The handler function for the route.
 * @return array The new routes array.
 */
function post(array $routes, string $uri, callable $handler): array
{
    $newRoutes = $routes;
    $newRoutes['POST'][$uri] = $handler;
    return $newRoutes;
}

/**
 * Registers a PUT route.
 *
 * @param array $routes The current routes array.
 * @param string $uri The URI to match.
 * @param callable $handler The handler function for the route.
 * @return array The new routes array.
 */
function put(array $routes, string $uri, callable $handler): array
{
    $newRoutes = $routes;
    $newRoutes['PUT'][$uri] = $handler;
    return $newRoutes;
}

/**
 * Registers a DELETE route.
 *
 * @param array $routes The current routes array.
 * @param string $uri The URI to match.
 * @param callable $handler The handler function for the route.
 * @return array The new routes array.
 */
function delete(array $routes, string $uri, callable $handler): array
{
    $newRoutes = $routes;
    $newRoutes['DELETE'][$uri] = $handler;
    return $newRoutes;
}

/**
 * Dispatches the request to the appropriate handler.
 *
 * @param array $routes The routes configuration.
 * @param array $request The incoming request array.
 * @return array The response array.
 */
function dispatch(array $routes, array $request): array
{
    $method = getMethod($request);
    $uri = getUri($request);

    $handlers = $routes[$method] ?? [];

    if (array_key_exists($uri, $handlers)) {
        return $handlers[$uri]($request);
    }

    // Handle 404 Not Found
    return createResponse('404 Not Found', 404);
}

/**
 * Handles the incoming HTTP request, dispatches it, and sends the response.
 * This is the main entry point for the router.
 *
 * @param array $routes The configured routes.
 * @return void
 */
function handleRequest(array $routes): void
{
    $rawContent = file_get_contents('php://input');
    $request = createRequest($_SERVER, $_GET, $_POST, $_FILES, $rawContent);
    $response = dispatch($routes, $request);
    sendResponse($response);
}
