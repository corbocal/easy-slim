<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Application\Middlewares;

use Corbocal\EasySlim\Enums\Http\HeadersEnum;
use Corbocal\EasySlim\Logger\LogFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Log\LoggerInterface;

/**
 * This middleware needs to be set HIGHER than the Slim RoutingMiddlesware
 */
class IncomingRequestMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected LoggerInterface $logger,
        protected int $requestIdSize = 8
    ) {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        // adding the request id if not present
        $currentRequestId = $request->getHeaderLine(HeadersEnum::X_REQUEST_ID->value);
        if (empty($currentRequestId)) {
            $id = self::generateRequestId($this->requestIdSize);
            $request = $request->withAddedHeader(HeadersEnum::X_REQUEST_ID->value, $id);
        }

        // adds the current microtime to the request
        /** @var ?float */
        $currentMicrotime = $request->getServerParams()[HeadersEnum::REQUEST_TIME_FLOAT->value];
        if (empty($currentMicrotime)) {
            $currentMicrotime = (string) microtime(true);
        }
        $request = $request->withAddedHeader(HeadersEnum::X_REQUEST_MICROTIME->value, (string) $currentMicrotime);

        // logging the request
        $log = LogFactory::requestLog($request);
        $this->logger->info($log->__tostring());

        return $handler->handle($request);
    }

    private static function generateRequestId(int $size): string
    {
        $size = \min(\max($size, 1), 16);

        return (string) (time() . "-" . bin2hex(random_bytes($size)));
    }
}
