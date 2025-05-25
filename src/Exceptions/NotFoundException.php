<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Exceptions;

class NotFoundException extends AbstractApiException
{
    public const int CODE = self::HTTP_NOT_FOUND;
}
