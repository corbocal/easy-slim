<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Application\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class PayloadTypesValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected readonly string $configFile,
        protected readonly string $configIndex
    ) {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $payload = $request->getParsedBody();
        return $handler->handle($request);
    }
}
