<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Logger\Dto;

use Corbocal\EasySlim\Logger\Dto\AbstractLog;

final readonly class Log extends AbstractLog
{
    /**
     * @param ?string $level
     * @param ?string $requestId
     * @param ?string $file
     * @param ?int $line
     * @param ?array<mixed> $input
     * @param ?array<mixed> $output
     */
    public function __construct(
        ?string $level = null,
        ?string $requestId = null,
        public ?string $file = null,
        public ?int $line = null,
        public ?array $input = null,
        public ?array $output = null,
    ) {
        parent::__construct($level, $requestId);
    }
}
