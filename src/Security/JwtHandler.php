<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Security;

use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @todo WIP
 */
final class JwtHandler
{
    private function __construct()
    {
    }

    /**
     * @param Request $request PSR-7 request
     *
     * @return ?string The value extracted from the cookie or null if the cookie was not found.
     */
    public static function grabFromCookies(Request $request, ?string $cookieName = null): ?string
    {
        $index = $cookieName ?? 'FALLBACK';
        /** @var ?string */
        $cookie = $request->getCookieParams()[$index] ?? null;

        return $cookie;
    }

    /**
     * @param string $jwt The Base64-encoded token
     *
     * @return array<scalar|array<scalar>> The parsed JWT as an array
     */
    public static function decodeJwt(string $jwt): array
    {
        return [];
    }
}
