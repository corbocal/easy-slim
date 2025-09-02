<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Security;

use Corbocal\EasySlim\Security\Handlers\AbstractHandler;
use Corbocal\EasySlim\Security\AuthenticatorInterface;
use Corbocal\EasySlim\Traits\RequestTrait;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @todo WIP
 */
final class JwtHandler extends AbstractHandler implements AuthenticatorInterface
{
    use RequestTrait;

    public function __construct(
        protected Request $request
    ) {
    }

    public function handle(): bool
    {
        return true;
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
