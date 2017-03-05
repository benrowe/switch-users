<?php

namespace App\Http\Controllers;

use Zend\Diactoros\Response;

/**
 * Base controller
 */
abstract class AbstractController
{
    /**
     * return a rendered view as a string
     *
     * @param         $response
     * @param  string $path   view to render
     * @param  array $params arguments to pass to the view
     * @return string
     */
    protected function view($response, $path, array $params = [])
    {

        // implement
        ob_start();

        $__viewPath = \App\path(\App\config('view.path').'/'.strtr($path, '.', '/').'.php');

        // convert the params to local variables
        extract($params);

        require $__viewPath;

        return ob_get_contents();

    }
}
