<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Application\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class RequestIdMiddleware implements MiddlewareInterface
{
    public const string REQUEST_ID_HEADER = "X-REQUEST-ID";

    public function __construct()
    {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $currentRequestId = $request->getHeaderLine(self::REQUEST_ID_HEADER);
        $request = $request->withHeader(self::REQUEST_ID_HEADER, $currentRequestId . self::generateRequestId());

        return $handler->handle($request);
    }

    private static function generateRequestId(): string
    {
        return strval(time() . "-" . bin2hex(random_bytes(8)));
    }
}
