<?php

namespace App;

/**
 * This file contains global functions that provide quick and easy access to
 * this applications services,
 *
 * and
 *
 * global convenience functions
 */

/**
 * Get an instance of the application container
 *
 * @return \App\Support\Container
 */
function app()
{
    return \App\Support\Container::instance();
}

function config($key = null, $default = null)
{
    $cfg = app()->get('config');
    if (!$key) {
        return $cfg;
    }

    return $cfg->get($key, $default);
}

function path($relativePath = null)
{
    $path = app()->root();
    if ($relativePath !== null) {
        $path .= trim($relativePath, '/');
    }

    return $path;
}
