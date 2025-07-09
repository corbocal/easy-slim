<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Settings;

use Corbocal\EasySlim\Enums\PsrLevelsEnum;

final class Settings implements SettingsInterface
{
    public const string DATE_FORMAT_STDTMZ = "Y-m-d H:i:s T";
    public const string DATE_FORMAT_STD = "Y-m-d H:i:s";

    private const string APP_ROOT_DIR = "appRootDir";
    private const string DISPLAY_ERROR_DETAILS = "displayErrorDetails";
    private const string LOG_ERRORS = "logErrors";
    private const string LOG_ERROR_DETAILS = "logErrorDetails";
    private const string LOGGER_PATH = "loggerPath";
    private const string LOGGER_NAME = "loggerName";
    private const string LOGGER_MIN_LEVEL = "loggerMinLevel";
    private const string IS_PROD = "isProd";

    /**
     * @var array<string,int|float|string|bool|null>
     */
    private array $settings;

    public function __construct(
        string $appRoorDir
    ) {
        $this->settings[self::APP_ROOT_DIR] = $appRoorDir;
        $this->settings[self::DISPLAY_ERROR_DETAILS] = filter_var(getenv("DISPLAY_ERROR_DETAILS"), FILTER_VALIDATE_BOOL);
        $this->settings[self::LOG_ERRORS] = filter_var(getenv("LOG_ERRORS"), FILTER_VALIDATE_BOOL);
        $this->settings[self::LOG_ERROR_DETAILS] = filter_var(getenv("LOG_ERROR_DETAILS"), FILTER_VALIDATE_BOOL);
        $this->settings[self::LOGGER_PATH] = $appRoorDir . getenv("LOGGER_PATH");
        $this->settings[self::LOGGER_NAME] = getenv("LOGGER_NAME");
        $this->settings[self::LOGGER_MIN_LEVEL] = getenv("LOGGER_MIN_LEVEL");
        $this->settings[self::IS_PROD] = (bool) preg_match('/prod/i', (string) getenv("ENV"));
    }

    public function get(string $key = ''): mixed
    {
        return $this->settings[$key] ?? null;
    }

    public function getAppRootDir(): string
    {
        return (string) $this->settings[self::APP_ROOT_DIR];
    }

    public function getDisplayErrorDetails(): bool
    {
        return (bool) $this->settings[self::DISPLAY_ERROR_DETAILS];
    }

    public function getLogErrors(): bool
    {
        return (bool) $this->settings[self::LOG_ERRORS];
    }

    public function getLogErrorDetails(): bool
    {
        return (bool) $this->settings[self::LOG_ERROR_DETAILS];
    }

    public function getLoggerMinLevel(): PsrLevelsEnum
    {
        return PsrLevelsEnum::tryFrom((string) $this->settings[self::LOGGER_MIN_LEVEL]) ?? PsrLevelsEnum::DEBUG;
    }

    public function getLoggerName(): string
    {
        return (string) $this->settings[self::LOGGER_NAME];
    }

    public function getLoggerPath(): string
    {
        return (string) $this->settings[self::LOGGER_PATH];
    }

    public function isProd(): bool
    {
        return (bool) $this->settings[self::IS_PROD];
    }
}
