<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Traits;

use Corbocal\EasySlim\Enums\HttpCodesEnum;
use Corbocal\EasySlim\Enums\PsrLevelsEnum;
use Corbocal\EasySlim\Logger\CustomLogTransferObject;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;


trait CustomLogWrapperTrait
{
    // protected LoggerInterface $logger;
    // protected Request $request;

    /**
     * Returns the string representation of a `CustomLogTransfertObject` as a string
     * @param ?string $ressourceId
     * @param ?PsrLevelsEnum $level
     * @param ?HttpCodesEnum $httpCode
     * @param ?string $message
     * @param ?array<mixed> $payload
     * @param ?string $userId
     * @return string
     * @see CustomLogTransferObject
     */
    private function getCustomLogAsString(
        ?string $ressourceId,
        ?PsrLevelsEnum $level,
        ?HttpCodesEnum $httpCode,
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
     * @param HttpCodesEnum $httpCode
     * @param string $message
     * @param array<mixed> $payload
     * @param string $userId
     * @param PsrLevelsEnum $level
     * @return void
     */
    public function log(
        ?string $ressourceId = null,
        HttpCodesEnum $httpCode = HttpCodesEnum::OK,
        ?string $message = null,
        ?array $payload = [],
        ?string $userId = null,
        PsrLevelsEnum $level = PsrLevelsEnum::DEBUG
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
            PsrLevelsEnum::EMERGENCY->value => $this->logger->emergency($stuffToLog),
            PsrLevelsEnum::ALERT->value => $this->logger->alert($stuffToLog),
            PsrLevelsEnum::CRITICAL->value => $this->logger->critical($stuffToLog),
            PsrLevelsEnum::ERROR->value => $this->logger->error($stuffToLog),
            PsrLevelsEnum::WARNING->value => $this->logger->warning($stuffToLog),
            PsrLevelsEnum::NOTICE->value => $this->logger->notice($stuffToLog),
            PsrLevelsEnum::INFO->value => $this->logger->info($stuffToLog),
            PsrLevelsEnum::DEBUG->value => $this->logger->debug($stuffToLog),
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
            HttpCodesEnum::OK,
            $message,
            $payload,
            $userId,
            PsrLevelsEnum::DEBUG
        );
    }
}
