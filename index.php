<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

// Override the public path to __DIR__ so Vite knows the manifest is in the root build directory, 
// assuming you moved the contents of the public directory to the root of public_html.
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
