<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Security\Handlers;

use Corbocal\EasySlim\Security\AuthenticatorInterface;
use Corbocal\EasySlim\Traits\RequestTrait;
use Psr\Http\Message\ServerRequestInterface as Request;


abstract class AbstractHandler implements AuthenticatorInterface
{
    use RequestTrait;

    /**
     * @var array<string|array<string>>
     */
    protected array $dataForLog = [];

    public function __construct(
        protected Request $request
    ) {
    }

    public abstract function handle(): bool;

    public function getDataForLog(): array
    {
        return $this->dataForLog;
    }
}
