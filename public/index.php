<?php

require_once __DIR__ . '/../src/Core/functions.php';
require_once __DIR__ . '/../src/Http/Request.php';
require_once __DIR__ . '/../src/Http/Response.php';
require_once __DIR__ . '/../src/Http/Router.php';
require_once __DIR__ . '/../src/Controllers/PageController.php';
require_once __DIR__ . '/../src/Config/app.php';

use function Http\handleRequest;
use function Config\defineRoutes;

// Define routes
$routes = defineRoutes();

// Handle the incoming request
handleRequest($routes);
