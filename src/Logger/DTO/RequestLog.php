<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Logger\DTO;

use Corbocal\EasySlim\Enums\PsrLevelsEnum;
use Corbocal\EasySlim\Logger\DTO\AbstractLog;

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
}
