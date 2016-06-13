<?php
/**
 * Created by IntelliJ IDEA.
 * User: hanson
 * Date: 2016. 6. 13.
 * Time: 오후 3:17
 */
namespace BootCore {

    use Psr\Http\Message\RequestInterface;

    class BootCore
    {
        private $route = null;
        private $container = null;

        public function __construct()
        {
            $this->container = new \League\Container\Container;
            $this->container->share('response', \Zend\Diactoros\Response::class);
            $this->container->share('request', function () {
                return \Zend\Diactoros\ServerRequestFactory::fromGlobals(
                    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
                );
            });
            $this->container->share('emitter', \Zend\Diactoros\Response\SapiEmitter::class);

            $this->route = new \League\Route\RouteCollection($this->container);


        }

        /**
         * @return \League\Route\RouteCollection|null
         */
        public function getRoute()
        {
            return $this->route;
        }

        /**
         * Load Routes Here
         */
        public function loadRoutes()
        {

        }

        /**
         * @param RequestInterface $request
         */
        public function run($request = null)
        {
            // Process Routes
            if (is_null($request)) {
                $request = $this->container->get('request');
            }
            $response = $this->route->dispatch($request, $this->container->get('response'));
            $this->container->get('emitter')->emit($response);
        }
    }
}
