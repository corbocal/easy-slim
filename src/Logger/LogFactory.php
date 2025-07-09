<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Logger;

use Corbocal\EasySlim\Enums\Http\HeadersEnum;
use Corbocal\EasySlim\Logger\DTO\RequestLog;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

final readonly class LogFactory
{
    public static function requestLog(Request $request): RequestLog
    {
        $headersFilter = function (Request $request, array $headersTSearchFor): string {
            $values = [];
            $headers = $request->getServerParams();
            foreach ($headers as $key => $value) {
                if (in_array($key, $headersTSearchFor)) {
                    $values[] = $value;
                }
            }

            return implode("|", $values);
        };

        return new RequestLog(
            $request->getHeaderLine(HeadersEnum::X_REQUEST_ID->value),
            $request->getMethod(),
            $request->getUri()->__tostring(),
            RouteContext::fromRequest($request)->getRoute()?->getArguments(),
            $headersFilter($request, HeadersEnum::allForIp()),
            $request->getHeaderLine(HeadersEnum::USER_AGENT->value),
            $headersFilter($request, HeadersEnum::allForReferer()),
            (array) $request->getParsedBody()
        );
    }
}
