<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Exceptions\Http;

use Corbocal\EasySlim\Enums\Http\StatusCodesEnum;
use Corbocal\EasySlim\Exceptions\ApiException;

class ServiceUnavailableException extends ApiException
{
    public static StatusCodesEnum $httpCode = StatusCodesEnum::SERVICE_UNAVAILABLE;
}
