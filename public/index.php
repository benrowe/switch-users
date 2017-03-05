<?php

$app = require_once realpath(__DIR__.'/../bootstrap.php');

$app->run('app/Http/routes.php');
