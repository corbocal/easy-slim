<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Settings;

use Corbocal\EasySlim\Enums\Logger\PsrLevelsEnum;
use Corbocal\EasySlim\Settings\Settings;
use UnexpectedValueException;

final class EnvChecker
{
    public static function validateSettings(string $key, string|bool|int|float|null $value): void
    {
        switch ($key) {
            case Settings::APP_ROOT_DIR:
                if (realpath((string) $value) === false) {
                    throw new UnexpectedValueException("The appRootDir does not exist", 500);
                }
                break;
            case Settings::DISPLAY_ERROR_DEBUG_DETAILS:
                if (!is_bool($value)) {
                    throw new UnexpectedValueException("the DISPLAY_ERROR_DEBUG_DETAILS env var is expected to be a boolean", 500);
                }
                break;
            case Settings::LOG_ERRORS:
                if (!is_bool($value)) {
                    throw new UnexpectedValueException("the LOG_ERRORS env var is expected to be a boolean", 500);
                }
                break;
            case Settings::LOG_ERROR_DETAILS:
                if (!is_bool($value)) {
                    throw new UnexpectedValueException("the LOG_ERROR_DETAILS env var is expected to be a boolean", 500);
                }
                break;
            case Settings::LOGGER_MIN_LEVEL:
                if (PsrLevelsEnum::tryFrom((string) $value) === null) {
                    throw new UnexpectedValueException("The LOGGER_MIN_LEVEL is expected to be a PSR-3 compatible value.", 500);
                }
                break;
            case Settings::LOGGER_PATH:
                if (realpath((string) $value) === false) {
                    throw new UnexpectedValueException("The LOGGER_PATH is expected to be a path to a directory.", 500);
                }
                break;
            case Settings::LOGGER_NAME:
                if (!preg_match("/^[A-Za-z0-9\-\_]{2,20}$/", (string) $value)) {
                    throw new UnexpectedValueException(
                        "The LOGGER_NAME is expected to have between 2 and 20 alphanumeric, `-` and/or `_` characters.",
                        500
                    );
                }
                break;
        }
    }
}
