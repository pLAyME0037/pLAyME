<?php

declare(strict_types=1);

namespace Http;

/**
 * Represents an immutable HTTP response.
 *
 * @param string $body The response body.
 * @param int $statusCode The HTTP status code.
 * @param array $headers An associative array of HTTP headers.
 * @return array An associative array representing the response.
 */
function createResponse(string $body = '', int $statusCode = 200, array $headers = []): array
{
    return [
        'body' => $body,
        'status_code' => $statusCode,
        'headers' => $headers,
    ];
}

/**
 * Sets a header on the response.
 *
 * @param string $name The header name.
 * @param string $value The header value.
 * @param array $response The original response array.
 * @return array The new response array with the header set.
 */
function setHeader(string $name, string $value, array $response): array
{
    return array_merge($response, [
        'headers' => array_merge($response['headers'], [$name => $value])
    ]);
}

/**
 * Sets the status code on the response.
 *
 * @param int $statusCode The HTTP status code.
 * @param array $response The original response array.
 * @return array The new response array with the status code set.
 */
function setStatusCode(int $statusCode, array $response): array
{
    return array_merge($response, ['status_code' => $statusCode]);
}

/**
 * Sends the HTTP response to the client.
 *
 * @param array $response The response array.
 * @return void
 */
function sendResponse(array $response): void
{
    http_response_code($response['status_code']);
    foreach ($response['headers'] as $name => $value) {
        header("{$name}: {$value}");
    }
    echo $response['body'];
}

/**
 * Creates a JSON response.
 *
 * @param mixed $data The data to encode as JSON.
 * @param int $statusCode The HTTP status code.
 * @param array $headers Additional headers.
 * @return array The JSON response array.
 */
function jsonResponse(mixed $data, int $statusCode = 200, array $headers = []): array
{
    $jsonHeaders = array_merge(['Content-Type' => 'application/json'], $headers);
    return createResponse(json_encode($data), $statusCode, $jsonHeaders);
}

/**
 * Creates a redirect response.
 *
 * @param string $location The URL to redirect to.
 * @param int $statusCode The HTTP status code for the redirect (e.g., 302, 301).
 * @return array The redirect response array.
 */
function redirectResponse(string $location, int $statusCode = 302): array
{
    return createResponse('', $statusCode, ['Location' => $location]);
}
