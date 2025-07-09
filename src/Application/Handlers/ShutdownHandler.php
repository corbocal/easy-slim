<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Application\Handlers;

use Corbocal\EasySlim\Enums\Http\HeadersEnum;
use Corbocal\EasySlim\Enums\PsrLevelsEnum;
use Corbocal\EasySlim\Logger\DTO\Log;
use Corbocal\EasySlim\Traits\JsonTrait;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class ShutdownHandler
{
    use JsonTrait;

    public function __construct(
        private bool $displayErrorDetails,
        private bool $logErrors,
        private bool $logErrorDetails,
        protected LoggerInterface $logger,
        protected Request $request
    ) {
    }

    public function __invoke(): void
    {
        if (($error = error_get_last()) !== null) {
            if ($this->logErrors) {
                if (in_array($error['type'], [E_WARNING, E_NOTICE])) {
                    $level = PsrLevelsEnum::WARNING;
                } else {
                    $level = PsrLevelsEnum::CRITICAL;
                }
                $log = new Log(
                    $level->value,
                    $this->request->getHeaderLine(HeadersEnum::X_REQUEST_ID->value),
                    $error['file'],
                    $error['line'],
                    [],
                    ['message' => $error['message'], 'type' => $error['type']]
                );
                if ($this->logErrorDetails) {
                }
                $this->logger->critical($log->__tostring());
            }

            if (in_array($error['type'], [E_WARNING, E_NOTICE])) {
                return;
            }

            http_response_code(500);
            if ($this->displayErrorDetails) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($error);
            } else {
                echo json_encode([
                    'message' => "A fatal error occured",
                    'reference' => "API-FATAL-ERROR",
                    'elements' => [],
                ]);
            }
            die;
        }
    }
}
