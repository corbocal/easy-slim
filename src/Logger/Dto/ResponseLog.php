<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Logger\Dto;

use Corbocal\EasySlim\Enums\Logger\PsrLevelsEnum;
use Corbocal\EasySlim\Logger\Dto\AbstractLog;

final readonly class ResponseLog extends AbstractLog
{
    /**
     * @param ?string $level
     * @param ?string $requestId
     * @param ?int $httpCode
     * @param ?int $duration
     * @param ?string $file
     * @param ?int $line
     * @param ?array<mixed> $output
     */
    public function __construct(
        ?string $level = null,
        ?string $requestId = null,
        public ?int $httpCode = null,
        public ?int $duration = null,
        public ?string $file = null,
        public ?int $line = null,
        public ?array $output = null,
    ) {
        parent::__construct($level ??= PsrLevelsEnum::INFO->value, $requestId);
    }
}
