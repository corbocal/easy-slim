<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Security;

interface AuthenticatorInterface
{
    public function handle(): bool;

    /**
     * @return array<string|array<string>>
     */
    public function getDataForLog(): array;
}
