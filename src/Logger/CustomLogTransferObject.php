<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Logger;

use Corbocal\EasySlim\Exceptions\AbstractApiException;
use Corbocal\EasySlim\Traits\HttpCodesTrait;
use Corbocal\EasySlim\Traits\HttpHeadersTrait;
use Corbocal\EasySlim\Traits\LogLevelsTrait;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\Route;

/**
 * Convenience object to format the json string to be logged
 */
class CustomLogTransferObject
{
    use LogLevelsTrait;
    use HttpCodesTrait;
    use HttpHeadersTrait;

    private const string SLIM_ROUTE = "__route__";
    public const string UUID_PATTERN = "/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/";

    /**
     * @param ?string $logLevel
     * @param ?string $date
     * @param ?string $requestId
     * @param ?float $duration
     * @param ?string $ip
     * @param ?string $host
     * @param ?string $referer
     * @param ?string $file
     * @param ?int $line
     * @param ?string $uri
     * @param ?int $httpCode
     * @param ?string $ressourceId
     * @param ?string $message
     * @param ?array<mixed> $output
     * @param ?string $userId
     */
    private function __construct(
        public ?string $logLevel = null,
        public ?string $date = null,
        public ?string $requestId = null,
        public ?float $duration = null,
        public ?string $ip = null,
        public ?string $host = null,
        public ?string $referer = null,
        public ?string $file = null,
        public ?int $line = null,
        public ?string $uri = null,
        public ?int $httpCode = null,
        public ?string $ressourceId = null,
        public ?string $message = null,
        public ?array $output = null,
        public ?string $userId = null,
    ) {
        $this->logLevel = $logLevel ?? self::PSR_DEBUG;
        $this->date ??= (new \DateTimeImmutable())->format("Y-m-d H:i:s");
        $this->requestId = $requestId;
        $requestTime = is_numeric($_SERVER[self::HEADER_REQUEST_TIME_FLOAT]) ? $_SERVER[self::HEADER_REQUEST_TIME_FLOAT] : null;
        $this->duration = $duration ?? round((microtime(true) - floatval($requestTime)) * 1000);
        $this->ip = $ip ?: null;
        $this->host = $host ?? gethostname() ?: null;
        $httpReferer = is_string($_SERVER[self::HEADER_HTTP_REFERER]) ? $_SERVER[self::HEADER_HTTP_REFERER] : null;
        $this->referer = $referer ?: $httpReferer ?: null;
        $this->file = $file;
        $this->line = $line;
        $stringUri = is_string($_SERVER[self::HEADER_HTTP_REFERER]) ? $_SERVER[self::HEADER_HTTP_REFERER] : null;
        $this->uri = $uri ?? $stringUri;
        $this->httpCode = $httpCode ?? 200;
        $this->ressourceId = $ressourceId;
        $this->message = $message;
        $this->output = $output;
        $this->userId = $userId;
    }

    public function __serialize(): array
    {
        $array = [];
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if ($value instanceof \DateTimeInterface) {
                $array[$key] = $value->format("Y-m-d H:i:s");
            } else {
                $array[$key] = $value;
            }
        }
        return $array;
    }

    public function __tostring(): string
    {
        return (string) json_encode($this->__serialize(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param ?string $level
     * @param ?string $file
     * @param ?int $line
     * @param ?int $httpCode
     * @param ?string $ressourceId
     * @param ?string $message
     * @param ?array<mixed> $output
     * @param ?string $userId
     * @param Request $request
     * @return CustomLogTransferObject
     */
    public static function create(
        ?string $level,
        ?string $file,
        ?int $line,
        ?int $httpCode,
        ?string $ressourceId,
        ?string $message,
        ?array $output,
        ?string $userId,
        ?Request $request
    ): self {
        return new CustomLogTransferObject(
            $level ?? self::PSR_ERROR,
            null,
            $request?->getHeaderLine(self::HEADER_X_REQUEST_ID),
            null,
            $request?->getHeaderLine(self::HEADER_HTTP_X_FORWARDED_FOR),
            $request?->getHeaderLine(self::HEADER_HTTP_X_FORWARDED_HOST),
            $request?->getHeaderLine(self::HEADER_HTTP_REFERER),
            $file,
            $line,
            $request?->getUri()->__toString(),
            $httpCode,
            $ressourceId ?? self::recoverIdentifiersParameters($request),
            $message,
            $output,
            $userId
        );
    }

    public static function createFromApiException(AbstractApiException $exception, Request $request): self
    {
        return self::create(
            self::PSR_ERROR,
            $exception->getFile(),
            $exception->getLine(),
            $exception->getHttpCode(),
            null,
            $exception->getErrorMessage(),
            $exception->getOutputforLog(),
            null,
            $request
        );
    }

    public static function createFromThrowable(\Throwable $e, Request $request): self
    {
        return self::create(
            self::PSR_ERROR,
            $e->getFile(),
            $e->getLine(),
            self::isHttpCode($e->getCode()) ? $e->getCode() : self::HTTP_INTERNAL_SERVER_ERROR,
            null,
            $e->getMessage(),
            self::formatOutputFromThrowable($e, $request),
            null,
            $request
        );
    }

    private static function recoverIdentifiersParameters(?Request $request, ?string $pattern = null): ?string
    {
        $result = null;
        $route = $request?->getAttribute(self::SLIM_ROUTE);
        if ($route instanceof Route) {
            $routeArgs = $route->getArguments();
            $filteredArgs = array_filter($routeArgs, function ($value) use ($pattern) {
                return (bool) preg_match($pattern ?? self::UUID_PATTERN, $value);
            });

            if (count($filteredArgs) > 1) {
                foreach ($filteredArgs as $key => $value) {
                    $result .= "$key = $value ";
                }
            } else {
                $result = array_values($filteredArgs)[0];
            }
        }

        return $result;
    }

    /**
     * @param \Throwable $e
     * @param Request $request
     * @return array{
     *  exception: string,
     *  payload: mixed
     * }
     */
    private static function formatOutputFromThrowable(\Throwable $e, Request $request): array
    {
        return [
            "exception" => get_class($e),
            "payload" => $request->getParsedBody(),
        ];
    }
}
