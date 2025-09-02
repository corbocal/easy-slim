<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Settings;

interface SettingsInterface
{
    public function get(string $key = ''): mixed;

    public function getAppRootDir(): string;
    public function isProd(): bool;

    public function getDisplayErrorDebugDetails(): bool;
    public function getLogErrors(): bool;
    public function getLogErrorDetails(): bool;

    public function getLoggerMinLevel(): string;
    public function getLoggerPath(): string;
    public function getLoggerName(): string;
}
