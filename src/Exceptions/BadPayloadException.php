<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Exceptions;

class BadPayloadException extends AbstractApiException
{
    public const int CODE = self::HTTP_BAD_REQUEST;
}
