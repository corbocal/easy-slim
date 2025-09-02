<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Middlewares;

use Corbocal\EasySlim\Exceptions\Http\ForbiddenException;
use Corbocal\EasySlim\Security\AuthenticationWrapper;
use Corbocal\EasySlim\Security\Enums\AuthenticatorsEnum;
use Corbocal\EasySlim\Security\Enums\ClearancesEnum;
use Corbocal\EasySlim\Settings\SettingsInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Log\LoggerInterface;

/**
 * @todo WIP
 */
class AuthenticatorMiddleware implements MiddlewareInterface
{
    /**
     * @param array<AuthenticatorsEnum> $authenticationModes
     * @param ClearancesEnum $clearance
     * @param LoggerInterface $logger
     * @param SettingsInterface $settings
     */
    public function __construct(
        protected array $authenticationModes,
        protected ClearancesEnum $clearance,
        protected LoggerInterface $logger,
        protected SettingsInterface $settings
    ) {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        if (!$this->settings->isProd()) {
            return $handler->handle($request);
        }

        $security = new AuthenticationWrapper(
            $this->authenticationModes,
            $this->clearance,
            $request,
            $this->settings
        );

        if (!$security->handle()) {
            throw new ForbiddenException(
                "You are not allowed to access this resource.",
                "FORBIDDEN",
                [],
                $security->getDataForLog()
            );
        }

        return $handler->handle($request);
    }
}
