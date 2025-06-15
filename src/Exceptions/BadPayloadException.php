<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Exceptions;

use Corbocal\EasySlim\Enums\HttpCodesEnum;

class BadPayloadException extends AbstractApiException
{
    public const HttpCodesEnum CODE = HttpCodesEnum::BAD_REQUEST;
}
