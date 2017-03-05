<?php

use App\Support\Container;
use League\Container\Container as DiContainer;

require_once 'vendor/autoload.php';

// load environment
try {
    (new Dotenv\Dotenv(__DIR__))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$app = new Container(new DiContainer(), realpath(__DIR__));

// load the config
$app->share('config', function () use ($app) {
    return new \Config\Repository(new \Config\Loader\FileLoader(\App\path('config')), getenv('APP_ENV'));
});
return $app;
