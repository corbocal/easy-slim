<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Security;

use Corbocal\EasySlim\Security\Handlers\AbstractHandler;
use Corbocal\EasySlim\Security\AuthenticatorInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @todo WIP
 */
final class IpHandler extends AbstractHandler implements AuthenticatorInterface
{
    public function __construct(
        protected Request $request
    ) {
    }

    public function handle(): bool
    {
        return true;
    }

    public static function checkIp(string $ip): bool
    {
        return true;
    }
}
