<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Exceptions;

class PaymentRequiredException extends AbstractApiException
{
    public const int CODE = self::HTTP_PAYMENT_REQUIRED;
}
