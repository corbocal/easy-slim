<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Traits;

use Slim\Http\Response;

trait ResponseTrait
{
    // protected Response $response;

    protected function responseJsonOk(
        string $ressourceId = "",
    ): Response {
        return $this->response
            ->withJson([
                "id" => $ressourceId,
            ], self::HTTP_OK, JSON_PRETTY_PRINT);
    }

    /**
     * @param array<mixed> $data
     * @return Response
     */
    protected function responseJsonCreated(
        array $data = [],
    ): Response {
        return $this->response
            ->withJson($data, self::HTTP_CREATED, JSON_PRETTY_PRINT);
    }

    protected function reponseNoContent(): Response
    {
        return $this->response->withStatus(self::HTTP_NO_CONTENT);
    }
}
