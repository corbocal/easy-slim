<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Security\Enums;

enum AuthenticatorsEnum: string
{
    case API_KEY = "API-KEY";
    case BEARER = "BEARER";
    case IP = "IP";
    case JWT = "JWT";
}
