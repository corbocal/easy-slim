<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Exceptions;

use Corbocal\EasySlim\Enums\Http\StatusCodesEnum;

class ApiException extends \Exception
{
    public static StatusCodesEnum $httpCode = StatusCodesEnum::INTERNAL_SERVER_ERROR;

    /**
     * @param string $message The message to display to the client.
     * @param string $reference A preferably unique string used to identify the error client- or server-side.
     * @param mixed[] $elements Something to display to the client that explains the error.
     * @param mixed[] $metadata Something to log backend details, but will not be displayed to the client.
     */
    public function __construct(
        string $message,
        protected readonly string $reference,
        /** @var array<mixed> */
        protected readonly array $elements = [],
        /** @var array<mixed> */
        protected readonly array $metadata = [],
        ?StatusCodesEnum $code = null
    ) {
        parent::__construct(
            $message,
            $code->value ?? static::$httpCode->value
        );
        $code !== null ? static::$httpCode = $code : StatusCodesEnum::INTERNAL_SERVER_ERROR;
    }

    public function getHttpCode(): StatusCodesEnum
    {
        return static::$httpCode;
    }

    /**
     * @return mixed[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @return mixed[]
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
