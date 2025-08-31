<?php

declare(strict_types=1);

namespace Http;

/**
 * Represents an immutable HTTP request.
 *
 * @param array $server The $_SERVER superglobal.
 * @param array $get The $_GET superglobal.
 * @param array $post The $_POST superglobal.
 * @param array $files The $_FILES superglobal.
 * @param string $content The raw request body content.
 * @return array An associative array representing the request.
 */
function createRequest(array $server, array $get, array $post, array $files, string $content): array
{
    $headers = [];
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
    } else {
        foreach ($server as $name => $value) {
            if (strpos($name, 'HTTP_') === 0) {
                $headerName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $headers[$headerName] = $value;
            }
        }
    }

    return [
        'method' => $server['REQUEST_METHOD'] ?? 'GET',
        'uri' => parse_url($server['REQUEST_URI'] ?? '/', PHP_URL_PATH),
        'query' => $get,
        'body' => $post,
        'files' => $files,
        'headers' => $headers,
        'raw_content' => $content,
        'server' => $server,
    ];
}

/**
 * Gets a specific header from the request.
 *
 * @param string $headerName The name of the header.
 * @param array $request The request array.
 * @return string|null The header value or null if not found.
 */
function getHeader(string $headerName, array $request): ?string
{
    $headers = $request['headers'] ?? [];
    $lowerHeaderName = strtolower($headerName);
    foreach ($headers as $name => $value) {
        if (strtolower($name) === $lowerHeaderName) {
            return $value;
        }
    }
    return null;
}

/**
 * Gets a query parameter from the request.
 *
 * @param string $paramName The name of the query parameter.
 * @param array $request The request array.
 * @return string|null The query parameter value or null if not found.
 */
function getQueryParam(string $paramName, array $request): ?string
{
    return $request['query'][$paramName] ?? null;
}

/**
 * Gets a body parameter from the request.
 *
 * @param string $paramName The name of the body parameter.
 * @param array $request The request array.
 * @return string|null The body parameter value or null if not found.
 */
function getBodyParam(string $paramName, array $request): ?string
{
    return $request['body'][$paramName] ?? null;
}

/**
 * Gets the request method.
 *
 * @param array $request The request array.
 * @return string The request method (e.g., 'GET', 'POST').
 */
function getMethod(array $request): string
{
    return $request['method'];
}

/**
 * Gets the request URI.
 *
 * @param array $request The request array.
 * @return string The request URI.
 */
function getUri(array $request): string
{
    return $request['uri'];
}
