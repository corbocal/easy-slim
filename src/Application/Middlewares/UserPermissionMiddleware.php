<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Application\Middlewares;

use Corbocal\EasySlim\Exceptions\Http\ForbiddenException;
use Corbocal\EasySlim\Exceptions\Http\UnauthenticatedException;
use Corbocal\EasySlim\Security\JwtHandler;
use Corbocal\EasySlim\Settings\SettingsInterface;
use Corbocal\EasySlim\Traits\JsonTrait;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Log\LoggerInterface;

/**
 * @todo WIP
 */
class UserPermissionMiddleware implements MiddlewareInterface
{
    use JsonTrait;

    /**
     * @param array<string> $neededPermissions
     * @param array<string> $allowedUsers
     * @param LoggerInterface $logger
     * @param SettingsInterface $settings
     */
    public function __construct(
        protected array $neededPermissions,
        protected array $allowedUsers,
        protected LoggerInterface $logger,
        protected SettingsInterface $settings
    ) {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        if (!$this->settings->isProd()) {
            return $handler->handle($request);
        }

        $jwtBase64 = JwtHandler::grabFromCookies($request);

        if ($jwtBase64 === null) {
            throw new UnauthenticatedException(
                "No JWT were found in the cookies.",
                "API-USER-PERMISSION-NO-JWT"
            );
        }

        $jwt = base64_decode($jwtBase64);

        try {
            /**
             * @var array<scalar|array<string>>
             */
            $jwtData = JwtHandler::decodeJwt($jwtBase64);
        } catch (Exception $e) {
            throw new UnauthenticatedException(
                "An error occured when trying to recoverer the JWT data",
                "API-USER-PERMISSION-JWT-ERROR",
                [],
                [$jwt, $e->__tostring()]
            );
        }

        /** @var ?string */
        $userName = $jwtData['user'] ?? null;
        /** @var ?string[] */
        $userPermissions = $jwtData['permissions'] ?? null;
        if (empty($userName) || empty($userPermissions)) {
            throw new UnauthenticatedException(
                "An error occured when reading the JWT.",
                "API-USER-PERMISSION-JWT-MISSING-DATA",
                [],
                [$jwt]
            );
        }

        if ($this->isUserAllowed($userName) || $this->checkPermissions($userPermissions)) {
            $this->logger->alert($this->jsonEncode($jwt));

            return $handler->handle($request);
        }

        throw new ForbiddenException(
            "API-USER-PERMISSION-JWT-FORBIDEN",
            "Your level of permission does not allow you to access this route",
            [],
            [$jwt]
        );
    }

    /**
     * @param array<string> $userPermissions
     *
     * @return bool
     */
    private function checkPermissions(?array $userPermissions): bool
    {
        return true;
    }

    /**
     * @param ?string $userName
     *
     * @return bool
     */
    private function isUserAllowed(?string $userName = null): bool
    {
        return true;
    }
}
