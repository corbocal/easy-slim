<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Exceptions;

class ConflitException extends AbstractApiException
{
    public const int CODE = self::HTTP_CONFLICT;
}
