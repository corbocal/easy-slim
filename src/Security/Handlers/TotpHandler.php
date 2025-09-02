<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Security;

use Corbocal\EasySlim\Security\AuthenticatorInterface;
use Corbocal\EasySlim\Security\Handlers\AbstractHandler;
use Corbocal\EasySlim\Settings\SettingsInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @todo WIP
 */
final class TotpHandler extends AbstractHandler implements AuthenticatorInterface
{
    public const string KEY_OTP = "otp";
    public const string KEY_USER_IDENTIFIER = "identifier";

    public static int $startTimestamp = 0;
    public static int $timeInterval = 20;
    public static string $algorithm = "sha1";

    protected string $clientOtp;
    protected string $clientIdentifier;

    public function __construct(Request $request, protected SettingsInterface $settings)
    {
        $parsed = $this->grabParsedBody();
        $this->clientOtp = $parsed[self::KEY_OTP] ?? "";
        $this->clientIdentifier = $parsed[self::KEY_USER_IDENTIFIER] ?? "";
        // todo getenv secret salt
    }

    public function handle(): bool
    {
        $secretKeyForThisUser = $this->retrieveUserSecretKey();
        $currentOtp = $this->calculateOtps($secretKeyForThisUser);

        return strcmp($this->clientOtp, $currentOtp) === 0;
    }


    private function retrieveUserSecretKey(): ?string
    {
        $secretKey = null;

        return "truc" . $this->clientIdentifier;

        if ($secret === "") {
            // throw new UnauthenticatedException("You do not exist.");
        }
    }


    private function calculateOtps(
        #[\SensitiveParameter]
        string $secretKey
    ): string {
        return "truc";
    }

    public static function generateNewSecretKey(int $length = 16, string $salt): string
    {
        $strongResult = false;
        while (!$strongResult) {
            $key = openssl_random_pseudo_bytes($length, $strongResult);
        }

        return $key;
    }
}
