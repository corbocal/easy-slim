<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Exceptions;

use Corbocal\EasySlim\Enums\HttpCodesEnum;

abstract class AbstractApiException extends \Exception
{
    protected const HttpCodesEnum CODE = HttpCodesEnum::INTERNAL_SERVER_ERROR;
    protected readonly HttpCodesEnum $httpCode;
    protected readonly string $errorMessage;

    protected readonly string $errorReference;

    /**
     * @var array<string,string>
     */
    protected readonly array $errorDetails;

    /**
     * @var array<string,string>
     */
    protected readonly array $metadata;

    /**
     * @var array<string,array<mixed>>
     */
    protected readonly array $payload;

    /**
     * @param string $errorMessage
     * @param string $errorReference a preferably unique string used to identify the error client-side
     * @param array<string,string> $errorDetails Something to display to the client
     * @param array<string,array<mixed>> $payload
     * @param array<string,string> $metadata Something used for logging/store lot of backend details, but will not be displayed to the client
     */
    public function __construct(
        string $errorMessage,
        string $errorReference = "",
        array $errorDetails = [],
        array $metadata = [],
        array $payload = [],
    ) {
        $code = static::CODE;
        parent::__construct($errorMessage, $code->value);
        $this->httpCode = $code;
        $this->errorMessage = $errorMessage;
        $this->errorReference = $errorReference;
        $this->errorDetails = $errorDetails;
        $this->payload = $payload;
        $metadata['exception'] = get_called_class();
        $this->metadata = $metadata;
    }

    public function getHttpCode(): int
    {
        return $this->getCode();
    }

    public function getErrorMessage(): string
    {
        return $this->getMessage();
    }

    /**
     * @return array<string,string>
     */
    public function getErrorDetails(): array
    {
        return $this->errorDetails;
    }

    public function getErrorReference(): string
    {
        return $this->errorReference;
    }

    /**
     * @return array<string,string>
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * @return array{
     *  errorReference: string,
     *  errorMessage: string,
     *  errorDetails: string[],
     *  metadata: string[],
     *  payload: array<string,mixed>
     * }
     */
    public function getOutputforLog(): array
    {
        return [
            'errorReference' => $this->getErrorReference(),
            'errorMessage' => $this->getErrorMessage(),
            'errorDetails' => $this->getErrorDetails(),
            'payload' => $this->payload,
            'metadata' => $this->getMetadata(),
        ];
    }
}
