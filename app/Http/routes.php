<?php

use App\Http\Controllers\UserController;

/**
 * @var \League\Route\RouteCollection $route;
 */
$route->map('GET', '/', UserController::class.'::index');
$route->map('GET', '/user/{id}', UserController::class.'::detail');
$route->map('GET', '/user', UserController::class.'::ajax');
