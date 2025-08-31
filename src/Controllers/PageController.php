<?php

declare (strict_types = 1);

namespace Controllers;

use function Http\createRequest;
use function Http\createResponse;
use function Http\jsonResponse;

/**
 * Renders a view within the main layout.
 *
 * @param string $viewName The name of the view file (without .php extension).
 * @param array $data Data to pass to the view.
 * @return string The rendered HTML content.
 */
function renderView(string $viewName, array $data = []): string
{
    $viewPath = __DIR__ . "/../Views/{$viewName}.php";

    if (!file_exists($viewPath)) {
        // In a real application, you might throw an exception or handle this differently
        return "View not found: {$viewName}";
    }

    // Use EXTR_SKIP to prevent overwriting existing variables
    extract($data, EXTR_SKIP);

    // Start output buffering for the specific view
    ob_start();
    require $viewPath;
    $content = ob_get_clean(); // Get the view content

    // Start output buffering for the layout
    ob_start();
    require __DIR__ . "/../Views/layout.php"; // Include the layout
    return ob_get_clean();                    // Get the full page content
}

/**
 * Handles the home page request.
 *
 * @param array $request The incoming request array.
 * @return array The response array.
 */
function home(array $request): array
{
    $body = renderView('home', [
        'title'   => 'Home Page',
        'content' => 'Welcome to the Functional Framework!']);
    return createResponse($body);
}

/**
 * Handles the Menu page request.
 *
 * @param array $request The incoming request array.
 * @return array The response array.
 */
function menuPage(array $request): array
{
    $body = renderView('menu', ['title' => 'Menu']);
    return createResponse($body);
}

/**
 * Handles the HTML Project SC page request.
 *
 * @param array $request The incoming request array.
 * @return array The response array.
 */
function htmlProjectScPage(array $request): array
{
    $body = renderView('html-project-sc', ['title' => 'HTML Website']);
    return createResponse($body);
}

function linuxRelatedPages($request, $viewName) {
    $body = renderView("linuxRelated/{$viewName}");
    return createResponse($body);
}

/**
 * Renders a WU Project page.
 *
 * @param array $request The incoming request array.
 * @param string $viewName The name of the view file in src/Views/wu_project/.
 * @return array The response array.
 */
function wuProjectPage(array $request, string $viewName): array
{
    $body = renderView("wu_project/{$viewName}");
    return createResponse($body);
}

function randLessonPages($request, $viewName): array {
    $body = renderView("lesson/{$viewName}");
    return createResponse($body);
}

/**
 * Handles the login page request.
 *
 * @param array $request The incoming request array.
 * @return array The response array.
 */
function login(array $request): array
{
    $body = renderView('login', ['title' => 'Login Page']);
    return createResponse($body);
}

/**
 * Handles the register page request.
 *
 * @param array $request The incoming request array.
 * @return array The response array.
 */
function register(array $request): array
{
    $body = renderView('register', ['title' => 'Register Page']);
    return createResponse($body);
}

/**
 * Handles the contact info page request.
 *
 * @param array $request The incoming request array.
 * @return array The response array.
 */
function contactInfo(array $request): array
{
    $body = renderView('contact-info', ['title' => 'Contact Information']);
    return createResponse($body);
}

/**
 * Example of an API endpoint.
 *
 * @param array $request The incoming request array.
 * @return array The JSON response array.
 */
function apiExample(array $request): array
{
    return jsonResponse(['status' => 'success', 'data' => ['message' => 'This is an API response.']]);
}

/**
 * Handles the pLAyME Home page request.
 *
 * @param array $request The incoming request array.
 * @return array The response array.
 */
function indexPage(array $request): array
{
    $body = renderView('index', ['title' => 'pLAyME Home']);
    return createResponse($body);
}
