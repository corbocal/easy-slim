<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Settings;

use Corbocal\EasySlim\Enums\Logger\PsrLevelsEnum;
use Corbocal\EasySlim\Settings\EnvChecker;
use UnexpectedValueException;

final class Settings implements SettingsInterface
{
    public const string DATE_FORMAT_STDTMZ = "Y-m-d H:i:s T";
    public const string DATE_FORMAT_STD = "Y-m-d H:i:s";

    public const string APP_ROOT_DIR = "APP_ROOT_DIR";
    public const string ENV = "ENV";
    public const string IS_PROD = "IS_PROD";
    public const string DISPLAY_ERROR_DEBUG_DETAILS = "DISPLAY_ERROR_DEBUG_DETAILS";
    public const string LOG_ERRORS = "LOG_ERRORS";
    public const string LOG_ERROR_DETAILS = "LOG_ERROR_DETAILS";
    public const string LOGGER_MIN_LEVEL = "LOGGER_MIN_LEVEL";
    public const string LOGGER_PATH = "LOGGER_PATH";
    public const string LOGGER_NAME = "LOGGER_NAME";

    /**
     * @var array<string,scalar|null>
     */
    private array $settings;

    public function __construct(
        string $appRoorDir
    ) {
        EnvChecker::validateSettings(self::APP_ROOT_DIR, $appRoorDir);
        $this->settings[self::APP_ROOT_DIR] = $appRoorDir;

        $val = (bool) preg_match('/prod/i', (string) getenv(self::ENV));
        EnvChecker::validateSettings(self::IS_PROD, $val);
        $this->settings[self::IS_PROD] = $val;

        $val = filter_var(getenv(self::DISPLAY_ERROR_DEBUG_DETAILS), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        EnvChecker::validateSettings(self::DISPLAY_ERROR_DEBUG_DETAILS, $val);
        $this->settings[self::DISPLAY_ERROR_DEBUG_DETAILS] = $val;

        $val = filter_var(getenv(self::LOG_ERRORS), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        EnvChecker::validateSettings(self::LOG_ERRORS, $val);
        $this->settings[self::LOG_ERRORS] = $val;

        $val = filter_var(getenv(self::LOG_ERROR_DETAILS), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        EnvChecker::validateSettings(self::LOG_ERROR_DETAILS, $val);
        $this->settings[self::LOG_ERROR_DETAILS] = $val;

        $val = (string) getenv(self::LOGGER_MIN_LEVEL);
        EnvChecker::validateSettings(self::LOGGER_MIN_LEVEL, $val);
        $this->settings[self::LOGGER_MIN_LEVEL] = $val;

        $val = $appRoorDir . getenv(self::LOGGER_PATH);
        EnvChecker::validateSettings(self::LOGGER_PATH, $val);
        $this->settings[self::LOGGER_PATH] = $val;

        $val = getenv(self::LOGGER_NAME);
        EnvChecker::validateSettings(self::LOGGER_NAME, $val);
        $this->settings[self::LOGGER_NAME] = $val;
    }

    public function get(string $key = ''): string|bool|int|float|null
    {
        return $this->settings[$key] ?? null;
    }

    public function getAppRootDir(): string
    {
        return (string) $this->settings[self::APP_ROOT_DIR];
    }

    public function isProd(): bool
    {
        return (bool) $this->settings[self::IS_PROD];
    }

    public function getDisplayErrorDebugDetails(): bool
    {
        return (bool) $this->settings[self::DISPLAY_ERROR_DEBUG_DETAILS];
    }

    public function getLogErrors(): bool
    {
        return (bool) $this->settings[self::LOG_ERRORS];
    }

    public function getLogErrorDetails(): bool
    {
        return (bool) $this->settings[self::LOG_ERROR_DETAILS];
    }

    public function getLoggerMinLevel(): string
    {
        return (string) $this->settings[self::LOGGER_MIN_LEVEL];
    }

    public function getLoggerPath(): string
    {
        return (string) $this->settings[self::LOGGER_PATH];
    }

    public function getLoggerName(): string
    {
        return (string) $this->settings[self::LOGGER_NAME];
    }
}
