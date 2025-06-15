<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Enums;

enum MonologLevelsEnum: int
{
    case EMERGENCY = 600;
    case ALERT = 550;
    case CRITICAL = 500;
    case ERROR = 400;
    case WARNING = 300;
    case NOTICE = 250;
    case INFO = 200;
    case DEBUG = 100;

    /**
     * @return string[]
     */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}