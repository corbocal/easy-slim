<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Application\Actions;

use Corbocal\EasySlim\Enums\Http\HeadersEnum;
use Corbocal\EasySlim\Enums\Http\StatusCodesEnum;
use Corbocal\EasySlim\Enums\PsrLevelsEnum;
use Corbocal\EasySlim\Logger\DTO\ResponseLog;
use Corbocal\EasySlim\Logger\LoggerTrait;
use Corbocal\EasySlim\Traits\RequestTrait;
use Corbocal\EasySlim\Traits\ResponseTrait;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

abstract class AbstractAction
{
    use RequestTrait;
    use ResponseTrait;
    use LoggerTrait;

    /**
     * @var array<mixed>
     */
    protected array $arguments;

    public function __construct(
        protected LoggerInterface $logger
    ) {
    }

    /**
     * @param Request $request A PSR-7 request object.
     * @param Response $response A PSR-7 response object.
     * @param array<mixed> $arguments An array of route arguments (named placeholders).
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $arguments): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->arguments = $arguments;

        $this->response = $this->action();
        $this->logResponseIfSuccess();

        return $this->response;
    }

    public function getRouteArguments(): mixed
    {
        return $this->arguments;
    }

    public function getRouteArgument(string $name): mixed
    {
        return $this->arguments[$name];
    }

    private function logResponseIfSuccess(): void
    {
        $response = $this->response;
        if (!StatusCodesEnum::isError($response->getStatusCode())) {
            $response->getBody()->rewind();
            $log = new ResponseLog(
                PsrLevelsEnum::INFO->value,
                $this->request->getHeaderLine(HeadersEnum::X_REQUEST_ID->value),
                $response->getStatusCode(),
                $this->calculateRequestDuration(),
                static::class,
                null
            );

            $this->logger->info($log->__tostring());
        }
    }

    /**
     * Concrete method to implement the action logic.
     *
     * @return Response
     */
    abstract protected function action(): Response;
}
