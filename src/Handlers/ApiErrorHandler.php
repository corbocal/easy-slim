<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Handlers;

use Corbocal\EasySlim\Enums\Http\HeadersEnum;
use Corbocal\EasySlim\Enums\Http\StatusCodesEnum;
use Corbocal\EasySlim\Enums\Logger\PsrLevelsEnum;
use Corbocal\EasySlim\Exceptions\ApiException;
use Corbocal\EasySlim\Logger\Dto\ResponseLog;
use Corbocal\EasySlim\Traits\JsonTrait;
use Corbocal\EasySlim\Traits\RequestTrait;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpException;
use Slim\Interfaces\ErrorHandlerInterface;

class ApiErrorHandler implements ErrorHandlerInterface
{
    use RequestTrait;
    use JsonTrait;

    protected bool $displayErrorDetails = false;
    protected bool $logErrors = true;
    protected bool $logErrorDetails = false;
    protected ?string $contentType = null;
    protected ?string $method = null;
    protected ServerRequestInterface $request;
    protected \Throwable $exception;
    protected ResponseFactoryInterface $responseFactory;
    protected LoggerInterface $logger;

    /**
     * @var ?array<mixed>
     */
    protected ?array $backtrace;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        LoggerInterface $logger
    ) {
        $this->responseFactory = $responseFactory;
        $this->logger = $logger;
    }

    /**
     * @param ServerRequestInterface $request The most recent Request object
     * @param \Throwable $exception The caught Exception object
     * @param bool $displayErrorDetails Whether or not to display the error details
     * @param bool $logErrors Whether or not to log errors
     * @param bool $logErrorDetails Whether or not to log error details
     */
    public function __invoke(
        ServerRequestInterface $request,
        \Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        $this->displayErrorDetails = $displayErrorDetails;
        $this->logErrors = $logErrors;
        $this->logErrorDetails = $logErrorDetails;
        $this->request = $request;
        $this->exception = $exception;
        $this->backtrace = $this->useBacktrace();

        return $this->respond();
    }

    protected function respond(): ResponseInterface
    {
        $e = $this->exception;
        $responseData = [
            'message' => self::determineResponseMessage($e),
            'reference' => self::determineResponseReference($e),
            'elements' => self::determineResponseElements($e),
        ];

        if ($this->displayErrorDetails) {
            $responseData['dev'] = [
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'class' => $e::class,
                'metadata' => self::determineResponseMetadata($e),
                'backtrace' => $this->backtrace
            ];
        }

        $code = StatusCodesEnum::isActualHttpCode($e->getCode()) ? $e->getCode() : StatusCodesEnum::INTERNAL_SERVER_ERROR->value;
        if ($this->logErrors) {
            $dataToLog = new ResponseLog(
                PsrLevelsEnum::ERROR->value,
                $this->request->getHeaderLine(HeadersEnum::X_REQUEST_ID->value),
                $code,
                $this->calculateRequestDuration(),
                $e->getFile(),
                $e->getLine(),
                self::determineOutputForLog($e)
            )->__serialize();
            if ($this->logErrorDetails) {
                $dataToLog['backtrace'] = $this->backtrace;
            }

            $this->logger->error($this->jsonEncode($dataToLog));
        }

        $response = $this->responseFactory->createResponse($code);
        $response->getBody()->write($this->jsonEncode($responseData));
        $response = $response->withHeader('Content-Type', 'application/json');

        return $response;
    }

    private static function determineResponseMessage(\Throwable $e): string
    {
        if ($e instanceof ApiException || $e instanceof HttpException) {
            return $e->getMessage();
        }

        return "An unexpected error occured.";
    }

    private static function determineResponseReference(\Throwable $e): string
    {
        if ($e instanceof ApiException) {
            return $e->getReference();
        }

        if ($e instanceof HttpException) {
            return "API-HTTP-ERROR";
        }

        return "API-UNEXPECTED-ERROR";
    }

    /**
     * @param \Throwable $e
     *
     * @return array<mixed>
     */
    private static function determineResponseElements(\Throwable $e): array
    {
        if ($e instanceof ApiException) {
            return $e->getElements();
        }

        if ($e instanceof HttpException) {
            return [$e->getDescription()];
        }

        return [];
    }

    /**
     * @param \Throwable $e
     *
     * @return array<mixed>
     */
    private static function determineResponseMetadata(\Throwable $e): array
    {
        if ($e instanceof ApiException) {
            return $e->getMetadata();
        }

        if ($e instanceof \Error || !$e instanceof HttpException) {
            return [
                $e->getMessage()
            ];
        }

        return [];
    }

    /**
     * @param \Throwable $e
     * @return array<mixed>
     */
    private static function determineOutputForLog(\Throwable $e)
    {
        return [
            'message' => $e->getMessage(),
            'reference' => self::determineResponseReference($e),
            'elements' => self::determineResponseElements($e),
            'class' => $e::class,
            'metadata' => self::determineResponseMetadata($e),
        ];
    }

    /**
     * @return ?array<mixed>
     */
    private function useBacktrace(): ?array
    {
        if ($this->displayErrorDetails || ($this->logErrors && $this->logErrorDetails)) {
            $backtrace = $this->exception->getTrace();
            foreach ($backtrace as $key => &$value) {
                if (isset($backtrace[$key]["class"])) {
                    $class = $value["class"] ?? "";
                    $type = $value["type"] ?? "";
                    $function = $value["function"];
                    $backtrace[$key]["class"] = $class . $type . $function;
                    unset($value["type"]);
                    unset($value["function"]);
                }
            }
            return $backtrace;
        }

        return null;
    }
}
