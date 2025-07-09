<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Application\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SanitizerMiddleware implements MiddlewareInterface
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        return $this->process($request, $handler);
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $rawParsedBody = $request->getParsedBody();
        if ($rawParsedBody !== null) {
            $request = $request->withParsedBody(self::sanitize($rawParsedBody));
        }

        return $handler->handle($request);
    }


    private static function sanitize(array|object $body)
    {
        foreach ($body as $key => &$value) {
            if (is_array($value)) {
                $value = self::sanitize($value);
            }
            if (is_string($key)) {
                $body[$key] = trim($value);
            }
        }

        return $body;
    }
}