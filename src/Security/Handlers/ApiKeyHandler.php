<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Security;

use Corbocal\EasySlim\Security\AuthenticatorInterface;
use Corbocal\EasySlim\Security\Handlers\AbstractHandler;
use Corbocal\EasySlim\Traits\RequestTrait;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @todo WIP
 */
final class ApiKeyHandler extends AbstractHandler implements AuthenticatorInterface
{
    public function handle(): bool
    {
        return true;
    }
}
