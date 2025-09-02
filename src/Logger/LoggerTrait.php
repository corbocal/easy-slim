<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Logger;

use Corbocal\EasySlim\Enums\Http\HeadersEnum;
use Corbocal\EasySlim\Enums\Logger\PsrLevelsEnum;
use Corbocal\EasySlim\Logger\Dto\Log;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

/**
 * Wrapper for several log methods.
 *
 * Needs access to a LoggerInterface
 */
trait LoggerTrait
{
    /**
     * Log a narrowed form of the LogTransferObject.
     *
     * @param PsrLevelsEnum $level
     * @param ?array<mixed> $data
     * @param ?LoggerInterface $logger
     * @param ?Request $request
     *
     * @return void
     */
    public function log(
        PsrLevelsEnum $level,
        ?array $data = [],
        ?LoggerInterface $logger = null,
        ?Request $request = null
    ): void {
        // will find from where the logger has been called
        $backTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
        isset($this->request) ? $id = $this->request->getHeaderLine(HeadersEnum::X_REQUEST_ID->value) : $id = null;
        $log = new Log(
            $level->value,
            $id,
            $backTrace['file'] ?? null,
            $backTrace['line'] ?? null,
            null,
            $data
        );

        $logger ??= $this->logger;
        $logger->log($level->value, $log->__tostring());
    }

    /**
     * Log a narrowed form of the LogTransferObject with Emergency level
     *
     * @param ?array<mixed> $data
     * @param ?LoggerInterface $logger
     * @param ?Request $request
     *
     * @return void
     */
    public function logEmergency(
        ?array $data = [],
        ?LoggerInterface $logger = null,
        ?Request $request = null
    ): void {
        $this->log(PsrLevelsEnum::EMERGENCY, $data, $logger, $request);
    }

    /**
     * Log a narrowed form of the LogTransferObject with Alert level
     *
     * @param ?array<mixed> $data
     * @param ?LoggerInterface $logger
     * @param ?Request $request
     *
     * @return void
     */
    public function logAlert(
        ?array $data = [],
        ?LoggerInterface $logger = null,
        ?Request $request = null
    ): void {
        $this->log(PsrLevelsEnum::ALERT, $data, $logger, $request);
    }

    /**
     * Log a narrowed form of the LogTransferObject with Critical level
     *
     * @param ?array<mixed> $data
     * @param ?LoggerInterface $logger
     * @param ?Request $request
     *
     * @return void
     */
    public function logCritical(
        ?array $data = [],
        ?LoggerInterface $logger = null,
        ?Request $request = null
    ): void {
        $this->log(PsrLevelsEnum::CRITICAL, $data, $logger, $request);
    }

    /**
     * Log a narrowed form of the LogTransferObject with Error level
     *
     * @param ?array<mixed> $data
     * @param ?LoggerInterface $logger
     * @param ?Request $request
     *
     * @return void
     */
    public function logError(
        ?array $data = [],
        ?LoggerInterface $logger = null,
        ?Request $request = null
    ): void {
        $this->log(PsrLevelsEnum::ERROR, $data, $logger, $request);
    }

    /**
     * Log a narrowed form of the LogTransferObject with Warning level
     *
     * @param ?array<mixed> $data
     * @param ?LoggerInterface $logger
     * @param ?Request $request
     *
     * @return void
     */
    public function logWarning(
        ?array $data = [],
        ?LoggerInterface $logger = null,
        ?Request $request = null
    ): void {
        $this->log(PsrLevelsEnum::WARNING, $data, $logger, $request);
    }

    /**
     * Log a narrowed form of the LogTransferObject with Notice level
     *
     * @param ?array<mixed> $data
     * @param ?LoggerInterface $logger
     * @param ?Request $request
     *
     * @return void
     */
    public function logNotice(
        ?array $data = [],
        ?LoggerInterface $logger = null,
        ?Request $request = null
    ): void {
        $this->log(PsrLevelsEnum::NOTICE, $data, $logger, $request);
    }

    /**
     * Log a narrowed form of the LogTransferObject with Info level
     *
     * @param ?array<mixed> $data
     * @param ?LoggerInterface $logger
     * @param ?Request $request
     *
     * @return void
     */
    public function logInfo(
        ?array $data = [],
        ?LoggerInterface $logger = null,
        ?Request $request = null
    ): void {
        $this->log(PsrLevelsEnum::INFO, $data, $logger, $request);
    }

    /**
     * Log a narrowed form of the LogTransferObject with Debug level
     *
     * @param ?array<mixed> $data
     * @param ?LoggerInterface $logger
     * @param ?Request $request
     *
     * @return void
     */
    public function logDebug(
        ?array $data = [],
        ?LoggerInterface $logger = null,
        ?Request $request = null
    ): void {
        $this->log(PsrLevelsEnum::DEBUG, $data, $logger, $request);
    }
}
