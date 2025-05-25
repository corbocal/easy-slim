<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Actions;

use Corbocal\EasySlim\Actions\RequestTrait;
use Corbocal\EasySlim\Actions\ResponseTrait;
use Corbocal\EasySlim\Logger\LogWrapperTrait;
use Corbocal\EasySlim\Traits\HttpCodesTrait;
use Psr\Log\LoggerInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;

abstract class AbstractAction
{
    use HttpCodesTrait;
    use LogWrapperTrait;
    use RequestTrait;
    use ResponseTrait;

    protected Request $request;
    protected Response $response;

    /**
     * @var array<mixed>
     */
    protected array $args;

    public function __construct(
        protected LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array<mixed> $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        return $this->action();
    }

    /**
     * @return Response
     */
    abstract protected function action(): Response;
}
