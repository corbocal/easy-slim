<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Enums\Logger;

enum PsrLevelsEnum: string
{
    case EMERGENCY = "emergency";
    case ALERT = "alert";
    case CRITICAL = "critical";
    case ERROR = "error";
    case WARNING = "warning";
    case NOTICE = "notice";
    case INFO = "info";
    case DEBUG = "debug";
}
