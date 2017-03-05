<?php

use App\Support\Container;
use League\Container\Container as DiContainer;
use Illuminate\Database\Capsule\Manager as Capsule;

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

// setup db
$app->share('capsule', function () use ($app) {
    $capsule = new Capsule;

    $config = $app->get('config');

    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => $config['database.host'],
        'database'  => $config['database.database'],
        'username'  => $config['database.username'],
        'password'  => $config['database.password'],
        'port'      => $config['database.port'],
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);

    $capsule->setAsGlobal();

    // setup eloquent
    $capsule->bootEloquent();

    return $capsule;
});
// force the db setup to return
$app->get('capsule');

return $app;
