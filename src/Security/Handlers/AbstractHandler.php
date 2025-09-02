<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Security\Handlers;

use Corbocal\EasySlim\Security\AuthenticatorInterface;

abstract class AbstractHandler implements AuthenticatorInterface
{
    /**
     * @var array<string|array<string>>
     */
    protected array $dataForLog = [];

    public function __construct(
    ) {
    }

    public abstract function handle(): bool;

    public function getDataForLog(): array
    {
        return $this->dataForLog;
    }
}
