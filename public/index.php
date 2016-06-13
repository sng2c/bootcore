<?php

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file = __DIR__ . $path;
    if (is_file($file)) {
        return false;
    }
}

/**
 * Bootcore
 */
require(__DIR__ . '/../vendor/autoload.php');
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Stream;

$request = ServerRequestFactory::fromGlobals();

$server = Zend\Diactoros\Server::createServerFromRequest(
    function ($request, $response, $done) {
        /**
         * @var \Psr\Http\Message\ServerRequestInterface $request
         * @var \Psr\Http\Message\ResponseInterface $response
         * @var callable $done
         */
        $stream = new Stream('php://temp','w');
        $stream->write("Hello World");
        return $response->withBody($stream);
    },
    $request);

$server->listen();