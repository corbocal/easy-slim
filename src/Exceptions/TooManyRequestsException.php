<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Exceptions;

class TooManyRequestsException extends AbstractApiException
{
    public const int CODE = self::HTTP_TOO_MANY_REQUESTS;
}
