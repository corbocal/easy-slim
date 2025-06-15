<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Traits;

use Psr\Http\Message\ServerRequestInterface as Request;

trait RequestTrait
{
    // protected Request $request;

    /**
     * @return ?array<mixed>
     */
    public function getParsedBody(): ?array
    {
        $body = $this->request->getParsedBody();

        if (is_array($body)) {
            foreach ($body as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $body[$key] = serialize($value);
                }
            }
        }
        if (is_object($body)) {
            $body = get_object_vars($body);
        }

        return $body;
    }

    public function getAttribute(string $name): mixed
    {
        return $this->request->getAttribute($name);
    }

    /**
     * @return array<mixed>
     */
    public function getQueryParams(): array
    {
        return $this->request->getQueryParams();
    }
}
