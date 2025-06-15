<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Application\Actions;

use Corbocal\EasySlim\Traits\RequestTrait;
use Corbocal\EasySlim\Traits\ResponseTrait;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

abstract class AbstractRouteCallable
{
    use RequestTrait;
    use ResponseTrait;

    protected readonly Request $request;
    protected readonly Response $response;

    /**
     * @var array<mixed>
     */
    protected readonly array $arguments;

    public function __construct(
        protected readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param Request $request A PSR-7 request object.
     * @param Response $response A PSR-7 response object.
     * @param array<mixed> $arguments An array of route arguments (named placeholders).
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $arguments): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->arguments = $arguments;

        return $this->action();
    }

    public function getRouteArgument(string $name): mixed
    {
        return $this->arguments[$name];
    }

    /**
     * Concrete method to implement the action logic.
     * @return Response
     */
    abstract protected function action(): Response;
}