<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Traits;

use Corbocal\EasySlim\Enums\Http\StatusCodesEnum;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Wrapper for several response methods.
 *
 * Needs access to a PSR ResponseInterface
 */
trait ResponseTrait
{
    use JsonTrait;

    protected Response $response;

    /**
     * Returns a PSR response in the json format with the desired HTTP status code.
     *
     * @param array<mixed> $data Data to return.
     * @param StatusCodesEnum $httpStatusCode HTTP code for the response
     *
     * @return Response
     */
    protected function respondJson(array $data, StatusCodesEnum $httpStatusCode = StatusCodesEnum::OK): Response
    {
        $this->response->getBody()->write($this->jsonEncodePrettyPrintResponse($data));

        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($httpStatusCode->value);
    }

    /**
     * Returns a PSR response in the json format with 200 as the HTTP status code.
     *
     * @param array<mixed> $data The data to display as JSON
     *
     * @return Response
     */
    protected function respondJsonOk(array $data = []): Response
    {
        return $this->respondJson($data);
    }

    /**
     * Returns a PSR response in the json format with 201 as the HTTP status code.
     *
     * @param array<mixed> $data L'identifiant de la ressource à retourner.
     *
     * @return Response
     */
    protected function respondJsonCreated(array $data = []): Response
    {
        return $this->respondJson($data, StatusCodesEnum::CREATED);
    }

    /**
     * Returns a PSR response with 204 as the HTTP status code.
     *
     * @return Response
     */
    protected function respondNoContent(): Response
    {
        return $this->response->withStatus(StatusCodesEnum::NO_CONTENT->value);
    }
}
