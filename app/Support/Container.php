<?php

namespace App\Support;

use League\Container\Container as DiContainer;
use League\Container\ReflectionContainer;

/**
 * Application Container
 *
 * Light weight application container
 * Registers services, etc
 *
 * @package App\Support
 */
class Container
{
    /**
     * @var Container
     */
    private static $instance;

    /**
     * @var DiContainer
     */
    private $dependency;

    /**
     * Application path root
     *
     * @var string
     */
    private $pathRoot;

    /**
     * Application container constructor
     *
     * @param  DiContainer $di
     * @param  string $path application base path
     */
    public function __construct(DiContainer $di, $path)
    {
        // allow dependency injection using class Reflection
        $di->delegate(
            new ReflectionContainer
        );

        $this->dependency = $di;
        $this->pathRoot = rtrim($path, '/') . '/';

        self::$instance = $this;
    }

    /**
     * Get the instance of the container
     * @return Container
     */
    public static function instance()
    {
        return self::$instance;
    }

    /**
     * Forward any undefined methods to the DI container
     * This will enable the application instance to register injectable services
     *
     * @param  string $method
     * @param  array $params
     * @return mixed
     * @throws InvaldCallException
     */
    public function __call($method, $params)
    {
        if (!is_callable($this->dependency, $method)) {
            throw new InvalidCallException(sprintf('"%s()" method does not exist in "%s"', $method, get_class($this)));
        }

        return call_user_func_array([$this->dependency, $method], $params);
    }
}
