<?php

namespace App\Support;

use App\Exceptions\InvalidCallException;
use League\Container\Container as DiContainer;
use League\Container\ReflectionContainer;
use League\Route\RouteCollection;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Response;

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
     * @param  DiContainer $diContainer
     * @param  string $path application base path
     */
    public function __construct(DiContainer $diContainer, $path)
    {
        // allow dependency injection using class Reflection
        $diContainer->delegate(
            new ReflectionContainer
        );

        $this->dependency = $diContainer;
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

    public function root()
    {
        return $this->pathRoot;
    }

    /**
     * Run the http application
     * @param  string $routePath location of the routes file
     */
    public function run($routePath)
    {
        $route = $this->getRouteCollection($routePath);
        $response = $this->dispatchRoute($route);

        $this->get('emitter')->emit($response);
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
        if (!method_exists($this->dependency, $method)) {
            throw new InvalidCallException(sprintf('"%s()" method does not exist in "%s"', $method, get_class($this)));
        }

        return call_user_func_array([$this->dependency, $method], $params);
    }

    private function getRouteCollection($path): RouteCollection
    {
        $route = new RouteCollection($this->dependency);

        require $this->root().$path;

        return $route;
    }

    private function dispatchRoute(RouteCollection $route)
    {
        $psr7 = new DiactorosFactory();
        $response = $route->dispatch(
            $psr7->createRequest($this->get('request')),
            $psr7->createResponse($this->get('response'))
        );

        return $this->handleResponseTypes($response);
    }

    /**
     * @param ResponseInterface|string|array $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function handleResponseTypes($response)
    {
        // string
        if (is_string($response)) {
            return (new DiactorosFactory)->createResponse(new Response($response));
        }
        // array
        if (is_array($response)) {
            return (new DiactorosFactory)->createResponse(
                new Response(json_encode($response), 200, ['content-type' => 'application/json'])
            );
        }
        // catch unhandled responses
        if (!($response instanceof ResponseInterface)) {
            throw new \RuntimeException("Route response is unsupported");
        }
        return $response;
    }
}
