<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Security\Enums;

enum ClearancesEnum: string
{
    case ALL = "ALL";
    case ALL_BUT_ONE = "ALL_BUT_ONE";
    case AT_LEAST_ONE = "AT_LEAST_ONE";
    case AT_LEAST_TWO = "AT_LEAST_TWO";
}
