<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Logger;

use Corbocal\EasySlim\Traits\HttpCodesTrait;
use Corbocal\EasySlim\Traits\LogLevelsTrait;
use Psr\Log\LoggerInterface;
use Slim\Http\ServerRequest as Request;

trait LogWrapperTrait
{
    use HttpCodesTrait;
    use LogLevelsTrait;

    // protected LoggerInterface $logger;
    // protected Request $request;

    /**
     * Summary of getCustomLogAsString
     * @param ?string $ressourceId
     * @param ?string $level
     * @param ?int $httpCode
     * @param ?string $message
     * @param ?array<mixed> $payload
     * @param ?string $userId
     * @return string
     */
    private function getCustomLogAsString(
        ?string $ressourceId,
        ?string $level,
        ?int $httpCode,
        ?string $message,
        ?array $payload,
        ?string $userId
    ): string {
        $customLogObject = CustomLogTransferObject::create(
            $level,
            get_called_class(),
            intval(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3)[2]['line'] ?? 0) ?: null,
            $httpCode,
            $ressourceId,
            $message,
            ["payload" => $payload],
            $userId,
            $this->request
        );

        return $customLogObject->__tostring();
    }

    /**
     * @param string $ressourceId
     * @param int $httpCode
     * @param string $message
     * @param array<mixed> $payload
     * @param string $userId
     * @param string $level
     * @return void
     */
    public function log(
        ?string $ressourceId = null,
        int $httpCode = 100,
        ?string $message = null,
        ?array $payload = [],
        ?string $userId = null,
        string $level = self::PSR_INFO
    ): void {
        $stuffToLog = $this->getCustomLogAsString(
            $ressourceId,
            $level,
            $httpCode,
            $message,
            $payload,
            $userId
        );

        match ($level) {
            self::PSR_EMERGENCY => $this->logger->emergency($stuffToLog),
            self::PSR_ALERT => $this->logger->alert($stuffToLog),
            self::PSR_CRITICAL => $this->logger->critical($stuffToLog),
            self::PSR_ERROR => $this->logger->error($stuffToLog),
            self::PSR_WARNING => $this->logger->warning($stuffToLog),
            self::PSR_NOTICE => $this->logger->notice($stuffToLog),
            self::PSR_INFO => $this->logger->info($stuffToLog),
            self::PSR_DEBUG => $this->logger->debug($stuffToLog),
            default => $this->logger->info($stuffToLog),
        };
    }

    /**
     * @param string $ressourceId
     * @param string $message
     * @param array<mixed> $payload
     * @param string $userId
     * @return void
     */
    public function logOk(
        string $ressourceId = "",
        string $message = "OK",
        ?array $payload = null,
        ?string $userId = null
    ): void {
        $this->log(
            $ressourceId,
            self::HTTP_OK,
            $message,
            $payload,
            $userId,
            self::PSR_INFO
        );
    }
}
