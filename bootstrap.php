<?php

use App\Support\Container;

require_once 'vendor/autoload.php';

// load environment
try {
    (new Dotenv\Dotenv(__DIR__))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}
