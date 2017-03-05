<?php

use App\Http\Controllers\UserController;

/**
 * @var \League\Route\RouteCollection $route;
 */
$route->map('GET', '/', UserController::class.'::index');
