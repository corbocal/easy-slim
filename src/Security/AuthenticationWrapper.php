<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Security;

use Corbocal\EasySlim\Exceptions\Http\InternalServerErrorException;
use Corbocal\EasySlim\Security\Enums\AuthenticatorsEnum;
use Corbocal\EasySlim\Security\Enums\ClearancesEnum;
use Corbocal\EasySlim\Security\Handlers\AbstractHandler;
use Corbocal\EasySlim\Settings\SettingsInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @todo WIP
 */
final class AuthenticationWrapper extends AbstractHandler implements AuthenticatorInterface
{
    /**
     * @var array<string,bool>
     */
    protected array $resultMatrix = [];

    /**
     * @param array<AuthenticatorsEnum> $authenticationModes
     * @param ClearancesEnum $clearance
     * @param SettingsInterface $settings
     */
    public function __construct(
        protected array $authenticationModes,
        protected ClearancesEnum $clearance,
        protected Request $request,
        protected SettingsInterface $settings,
    ) {
    }

    public function handle(): bool
    {
        foreach ($this->authenticationModes as $authenticator) {
            $handler = $this->getAuthHandler($authenticator);
            $this->resultMatrix[$authenticator->value] = $handler->handle();
            $this->dataForLog[$authenticator->value] = $handler->getDataForLog();
            if ($this->testMatrix()) {
                return true;
            }
        }

        return false;
    }

    private function getAuthHandler(AuthenticatorsEnum $authenticator): AuthenticatorInterface
    {
        $handler = null;
        match ($authenticator) {
            AuthenticatorsEnum::API_KEY => $handler = new ApiKeyHandler($this->request),
            AuthenticatorsEnum::IP => $handler = new IpHandler($this->request),
            AuthenticatorsEnum::JWT => $handler = new JwtHandler($this->request),
        };

        if ($handler === null) {
            throw new InternalServerErrorException(
                "The $authenticator->value handler does not exist.",
                "NO-HANDLER-CLASS"
            );
        }

        return $handler;
    }

    private function testMatrix(): bool
    {
        $result = false;
        switch ($this->clearance->value) {
            case ClearancesEnum::ALL->value:
                # code...
                break;
            case ClearancesEnum::ALL_BUT_ONE->value:
                # code...
                break;
            case ClearancesEnum::AT_LEAST_ONE->value:
                # code...
                break;
            case ClearancesEnum::AT_LEAST_TWO->value:
                # code...
                break;
        }

        return $result;
    }

    // checks if the selected authenticators match the clearance level
    // e.g. if "AT_LEAST_TWO" are desired, authenticators array need to contain at least two elements
    private function validate(): void
    {

    }
}
