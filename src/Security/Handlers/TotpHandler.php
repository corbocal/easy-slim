<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Security;

use Corbocal\EasySlim\Exceptions\Http\UnauthenticatedException;
use Corbocal\EasySlim\Security\AuthenticatorInterface;
use Corbocal\EasySlim\Security\Handlers\AbstractHandler;

/**
 * @todo WIP
 */
final class TotpHandler extends AbstractHandler implements AuthenticatorInterface
{
    protected int $startTimestamp = 0;
    protected int $timeInterval = 15;

    public function __construct(
        protected string $clientOtp,
        protected string $clientIdentifier
    ) {
    }

    public function handle(): bool
    {
        $secretKeyForThisUser = self::retrieveUserSecretKey();
        $currentOtp = $this->calculateCurrentOtp($secretKeyForThisUser);

        return strcmp($this->clientOtp, $currentOtp) === 0;
    }

    private function calculateCurrentOtp(
        #[\SensitiveParameter]
        string $secretKey
    ): string {

        return "truc";
    }

    private function retrieveUserSecretKey(): ?string
    {
        $secret = null;

        return "truc" . $this->clientIdentifier;

        if ($secret === "") {
            // throw new UnauthenticatedException("You do not exist.");
        }
    }

    public static function generateNewSecretKey(int $length = 16): string
    {
        $strongResult = false;
        while (!$strongResult) {
            $key = openssl_random_pseudo_bytes($length, $strongResult);
        }

        return $key;
    }
}
