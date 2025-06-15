<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Logger;

use Corbocal\EasySlim\Enums\HttpCodesEnum;
use Corbocal\EasySlim\Enums\HttpHeadersEnum;
use Corbocal\EasySlim\Enums\PsrLevelsEnum;
use Corbocal\EasySlim\Exceptions\AbstractApiException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\Route;

/**
 * Convenience object to format the json string to be logged
 */
class CustomLogTransferObject
{
    public const string UUID_PATTERN = "/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i";
    private const string SLIM_ROUTE = "__route__";
    private const string INDEX_PAYLOAD = "payload";
    private const string INDEX_EXCEPTION = "exception";

    public ?string $level = null;
    public ?int $httpCode = null;

    /**
     * @param ?PsrLevelsEnum $level
     * @param ?string $date
     * @param ?string $requestId
     * @param ?float $duration
     * @param ?string $ip
     * @param ?string $host
     * @param ?string $referer
     * @param ?string $file
     * @param ?int $line
     * @param ?string $uri
     * @param ?HttpCodesEnum $httpCode
     * @param ?string $ressourceId
     * @param ?string $message
     * @param ?array<mixed> $output
     * @param ?string $userId
     */
    private function __construct(
        ?PsrLevelsEnum $level = null,
        public ?string $date = null,
        public ?string $requestId = null,
        public ?float $duration = null,
        public ?string $ip = null,
        public ?string $host = null,
        public ?string $referer = null,
        public ?string $file = null,
        public ?int $line = null,
        public ?string $uri = null,
        ?HttpCodesEnum $httpCode = null,
        public ?string $ressourceId = null,
        public ?string $message = null,
        public ?array $output = null,
        public ?string $userId = null,
    ) {
        $this->level = $level->value ?? PsrLevelsEnum::DEBUG->value;
        $this->date ??= (new \DateTimeImmutable())->format("Y-m-d H:i:s");
        $this->requestId = $requestId;
        $requestTime = is_numeric($_SERVER[HttpHeadersEnum::REQUEST_TIME_FLOAT->value]) ? $_SERVER[HttpHeadersEnum::REQUEST_TIME_FLOAT->value] : null;
        $this->duration = $duration ?? round((microtime(true) - floatval($requestTime)) * 1000);
        $this->ip = $ip ?: null;
        $this->host = $host ?? gethostname() ?: null;
        $httpReferer = is_string($_SERVER[HttpHeadersEnum::HTTP_REFERER->value]) ? $_SERVER[HttpHeadersEnum::HTTP_REFERER->value] : null;
        $this->referer = $referer ?: $httpReferer ?: null;
        $this->file = $file;
        $this->line = $line;
        $stringUri = is_string($_SERVER[HttpHeadersEnum::HTTP_REFERER->value]) ? $_SERVER[HttpHeadersEnum::HTTP_REFERER->value] : null;
        $this->uri = $uri ?? $stringUri;
        $this->httpCode = $httpCode->value ?? 200;
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
     * @param ?PsrLevelsEnum $level
     * @param ?string $file
     * @param ?int $line
     * @param ?HttpCodesEnum $httpCode
     * @param ?string $ressourceId
     * @param ?string $message
     * @param ?array<mixed> $output
     * @param ?string $userId
     * @param Request $request
     * @return CustomLogTransferObject
     */
    public static function create(
        ?PsrLevelsEnum $level,
        ?string $file,
        ?int $line,
        ?HttpCodesEnum $httpCode,
        ?string $ressourceId,
        ?string $message,
        ?array $output,
        ?string $userId,
        ?Request $request
    ): self {
        return new CustomLogTransferObject(
            $level ?? PsrLevelsEnum::DEBUG,
            null,
            $request?->getHeaderLine(HttpHeadersEnum::X_REQUEST_ID->value),
            null,
            $request?->getHeaderLine(HttpHeadersEnum::HTTP_X_FORWARDED_FOR->value),
            $request?->getHeaderLine(HttpHeadersEnum::HTTP_X_FORWARDED_HOST->value),
            $request?->getHeaderLine(HttpHeadersEnum::HTTP_REFERER->value),
            $file,
            $line,
            $request?->getUri()?->__toString(),
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
            PsrLevelsEnum::DEBUG,
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

    public static function createFromAnyThrowable(\Throwable $e, Request $request): self
    {
        return self::create(
            PsrLevelsEnum::ERROR,
            $e->getFile(),
            $e->getLine(),
            self::isHttpCode($e->getCode()) ? $e->getCode() : self::HTTP_INTERNAL_SERVER_ERROR,
            null,
            $e->getMessage(),
            [
                self::INDEX_EXCEPTION => get_class($e),
                self::INDEX_PAYLOAD => $request->getParsedBody(),
            ],
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

}
