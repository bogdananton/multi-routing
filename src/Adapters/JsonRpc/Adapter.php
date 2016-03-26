<?php
namespace MultiRouting\Adapters\JsonRpc;

use MultiRouting\Adapters\AdapterInterface;
use MultiRouting\Router;

class Adapter implements AdapterInterface
{

    /**
     * The adapter name used for registering and implementing other functionality
     */
    const name = 'JsonRpc';

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var string
     */
    protected $currentIntent;

    /**
     * JsonRpcAdapter constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param $uri
     * @param $intent
     * @param $action
     * @return Route
     */
    public function intent($uri, $intent, $action)
    {
        $this->currentIntent = $intent;

        return $this->router->post($uri, $action);
    }

    /**
     * @param $methods
     * @param $uri
     * @param $action
     * @return Route
     */
    public function buildRoute($methods, $uri, $action)
    {
        $route = new Route($methods, $uri, $action);
        $route->setIntent($this->currentIntent);

        return $route;
    }
}