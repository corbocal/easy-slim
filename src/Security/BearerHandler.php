<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Security;

use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @todo WIP
 */
final class BearerHandler
{
    private function __construct()
    {
    }

    public static function grabFromHeaders(Request $request, ?string $headerName = null): string
    {
        return "";
    }
}
