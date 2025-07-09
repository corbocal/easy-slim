<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Settings;

use Corbocal\EasySlim\Enums\PsrLevelsEnum;

interface SettingsInterface
{
    public function get(string $key = ''): mixed;

    public function getAppRootDir(): string;

    public function getDisplayErrorDetails(): bool;
    public function getLogErrors(): bool;
    public function getLogErrorDetails(): bool;

    public function getLoggerPath(): string;
    public function getLoggerName(): string;
    public function getLoggerMinLevel(): PsrLevelsEnum;

    public function isProd(): bool;
}
