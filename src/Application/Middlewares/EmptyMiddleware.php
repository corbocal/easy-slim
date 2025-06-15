<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Application\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class EmptyMiddleware implements MiddlewareInterface
{
    public function __construct()
    {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        return $handler->handle($request);
    }
}
