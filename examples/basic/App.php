<?php
namespace Example;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher as EventsDispatcher;
use Illuminate\Http\Request;
use MultiRouting\Router;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class App
{
    /**
     * The application IoC container
     *
     * @var Container
     */
    protected $container;

    /**
     * The application router
     *
     * @var Router
     */
    protected $router;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->container = new Container();

        $this->setRouter();
        $this->initRoutes();
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Set the application router
     */
    protected function setRouter()
    {
        $eventsDispatcher = new EventsDispatcher($this->container);

        $this->router = new Router($eventsDispatcher, $this->container);
        $this->router->useAdapters([
            'JsonRpc',
            'Soap',
            'Rest'
        ]);
    }

    protected function initRoutes()
    {
        require 'resources/routes.php';
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function run()
    {
        $request = Request::createFromBase(Request::createFromGlobals());

        return $this->handle($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        try {
            $response = $this->router->dispatch($request);
        } catch (NotFoundHttpException $e) {
            // parse request to return an error according to protocol
            exit();
        }

        $response->sendHeaders();
        $response->send();

        return $response;
    }
}

