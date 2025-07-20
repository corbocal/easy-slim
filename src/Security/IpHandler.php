<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Security;

use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @todo WIP
 */
final class IpHandler
{
    private function __construct()
    {
    }

    public static function checkIp(string $ip): bool
    {
        return true;
    }
}
