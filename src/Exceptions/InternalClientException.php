<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Exceptions;

class InternalClientException extends AbstractApiException
{
    public const int CODE = self::HTTP_INTERNAL_SERVER_ERROR;
}
