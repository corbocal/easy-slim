<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Logger\Dto;

use Corbocal\EasySlim\Enums\Http\HeadersEnum;
use Corbocal\EasySlim\Enums\Logger\PsrLevelsEnum;
use Corbocal\EasySlim\Logger\Dto\AbstractLog;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

final readonly class RequestLog extends AbstractLog
{
    /**
     * @param ?string $requestId
     * @param ?string $method
     * @param ?string $uri
     * @param ?array<mixed> $arguments
     * @param ?string $ip
     * @param ?string $userAgent
     * @param ?string $referer
     * @param ?array<mixed> $payload
     */
    public function __construct(
        ?string $requestId = null,
        public ?string $method = null,
        public ?string $uri = null,
        public ?array $arguments = null,
        public ?string $ip = null,
        public ?string $userAgent = null,
        public ?string $referer = null,
        public ?array $payload = null,
    ) {
        parent::__construct(PsrLevelsEnum::INFO->value, $requestId);
    }

    public static function createWithRequest(Request $request): self
    {
        return new self(
            $request->getHeaderLine(HeadersEnum::X_REQUEST_ID->value),
            $request->getMethod(),
            $request->getUri()->__tostring(),
            RouteContext::fromRequest($request)->getRoute()?->getArguments(),
            self::grabFromHeaders($request, HeadersEnum::allForIp()),
            $request->getHeaderLine(HeadersEnum::USER_AGENT->value),
            self::grabFromHeaders($request, HeadersEnum::allForReferer()),
            (array) $request->getParsedBody()
        );
    }

    /**
     * @param Request $request
     * @param string[] $headersTSearchFor
     * @return ?string
     */
    private static function grabFromHeaders(Request $request, array $headersTSearchFor): ?string
    {
        $values = null;
        $headers = $request->getServerParams();
        foreach ($headers as $key => $value) {
            if (in_array($key, $headersTSearchFor)) {
                $values[] = $value;
            }
        }

        return $values !== null ? implode("|", $values) : null;
    }
}
