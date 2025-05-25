<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Traits;

trait LogLevelsTrait
{
    public const int MONOLOG_EMERGENCY = 600;
    public const int MONOLOG_ALERT = 550;
    public const int MONOLOG_CRITICAL = 500;
    public const int MONOLOG_ERROR = 400;
    public const int MONOLOG_WARNING = 300;
    public const int MONOLOG_NOTICE = 250;
    public const int MONOLOG_INFO = 200;
    public const int MONOLOG_DEBUG = 100;

    public const string PSR_EMERGENCY = "emergency";
    public const string PSR_ALERT = "alert";
    public const string PSR_CRITICAL = "critical";
    public const string PSR_ERROR = "error";
    public const string PSR_WARNING = "warning";
    public const string PSR_NOTICE = "notice";
    public const string PSR_INFO = "info";
    public const string PSR_DEBUG = "debug";
}
