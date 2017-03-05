<?php

namespace App\Http\Controllers;

/**
 * Base controller
 */
abstract class AbstractController
{
    /**
     * return a rendered view as a string
     *
     *
     * @param  string $path   view to render
     * @param  array $params arguments to pass to the view
     * @return string
     */
    protected function view($path, array $params = [])
    {
        // implement
    }
}
