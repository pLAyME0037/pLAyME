<?php

declare (strict_types = 1);

namespace Config;

use function Http\createRouter;
use function Http\get;
use function Http\post;

/**
 * Defines the application routes.
 * This function is responsible for registering all the URIs and their
 * corresponding handler functions with the router.
 *
 * @return array The configured routes.
 */
function defineRoutes(): array
{
    $routes = createRouter();

    // Public routes
    $routes = get($routes, '/', fn($request) => \Controllers\indexPage($request));
    $routes = get($routes, '/login', fn($request) => \Controllers\login($request));
    $routes = post($routes, '/login', function ($request) {
        // Handle login logic here
        // For now, just redirect to home
        return \Http\redirectResponse('/');
    });
    $routes = get($routes, '/register', fn($request) => \Controllers\register($request));
    $routes = post($routes, '/register', function ($request) {
        // Handle registration logic here
        // For now, just redirect to home
        return \Http\redirectResponse('/');
    });
    $routes = get($routes, '/contact-info', fn($request) => \Controllers\contactInfo($request));

    // API routes
    $routes = get($routes, '/api/example', fn($request) => \Controllers\apiExample($request));

    // New Pages routes
    $routes = get($routes, '/html-project-sc', fn($request) => \Controllers\htmlProjectScPage($request));
    $routes = get($routes, '/menu', fn($request) => \Controllers\menuPage($request));

    $linuxRelated = [
        'grub_problem_in_duelBoot',
    ];
    foreach ($linuxRelated as $linux) {
        $routes = get(
            $routes, 
            "/linuxRelated/{$linux}", 
            fn($request) => \Controllers\linuxRelatedPages(
                $request,
                $linux
            )
        );
    }

    // WU Project routes
    $wuProjectPages = [
        'box-shadow', 'div', 'Form', 'lesson', 'nav', 'Picture', 'reading', 'shopping', 'video',
        'story/story', 'story/ch1', 'story/ch2', 'story/ch3', 'story/ch4', 'story/ch5', 'story/ch6', 'story/ch7', 'story/ch8',
    ];
    foreach ($wuProjectPages as $page) {
        $routes = get($routes, "/wu_project/{$page}", fn($request) => \Controllers\wuProjectPage($request, $page));
    }
    
    $randLesson = [
        'css_Usage', 'DSA', 'html_project_sc',
    ];
    foreach ($randLesson as $lesson) {
        $routes = get($routes, "/lesson/{$lesson}", fn($request) => \Controllers\randLessonPages($request, $lesson));
    }
    return $routes;
}

/**
 * Defines database configuration.
 *
 * @return array
 */
function databaseConfig(): array
{
    return [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'port'      => '3306',
        'database'  => 'mvc_framwork',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix'    => '',
    ];
}
