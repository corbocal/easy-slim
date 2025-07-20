<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Application\Middlewares;

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
     * @param array<ClearancesEnum> $clearances
     * @param LoggerInterface $logger
     * @param SettingsInterface $settings
     */
    public function __construct(
        protected array $authenticationModes,
        protected array $clearances,
        protected LoggerInterface $logger,
        protected SettingsInterface $settings
    ) {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        if (!$this->settings->isProd()) {
            return $handler->handle($request);
        }

        return $handler->handle($request);
    }
}
