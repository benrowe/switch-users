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

    /**
     * Get the root path for the application
     *
     * @return string
     */
    public function root()
    {
        return $this->pathRoot;
    }

    /**
     * Run the http application
     *
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

    /**
     * Load the collection of routes from the specified route file
     * @param  string          $path absolute path to the routes.php file
     * @return RouteCollection The route collection, loaded with the contents
     *                         from the routes file
     */
    private function getRouteCollection($path): RouteCollection
    {
        $route = new RouteCollection($this->dependency);

        // load in the route file
        require $this->root().$path;

        return $route;
    }

    private function dispatchRoute(RouteCollection $route)
    {
        $psr7 = new DiactorosFactory();
        $response = $route->dispatch(
            // create psr7 compatiable versions of the request/response oci_fetch_object
            // for the controllers to use
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
                new Response(
                    json_encode($response),
                    200,
                    ['content-type' => 'application/json']
                )
            );
        }
        // catch unhandled responses
        if (!($response instanceof ResponseInterface)) {
            throw new \RuntimeException("Route response is unsupported");
        }
        return $response;
    }
}
