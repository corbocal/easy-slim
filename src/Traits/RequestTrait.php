<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Traits;

use Corbocal\EasySlim\Enums\Http\HeadersEnum;
use Psr\Http\Message\ServerRequestInterface as Request;

trait RequestTrait
{
    protected Request $request;

    /**
     * Returns the number of ellapsed milliseconds between the moment the request entered the first middleware and this function call.
     *
     * @return int
     */
    public function calculateRequestDuration(): ?int
    {
        $result = null;
        $microtime = $this->request->getHeaderLine(HeadersEnum::X_REQUEST_MICROTIME->value);
        if (!empty($microtime)) {
            $now = microtime(true);
            $result = (int) ($now - (float) $microtime) * 1000;
        }
        return $result;
    }

    public function grabFromCookies(string $cookieName): ?string
    {
        $value = $this->request->getCookieParams()[$cookieName];

        return empty($value) ? null : (string) $value;
    }

    public function grabFromHeaders(string $header): ?string
    {
        $value = $this->request->getHeaderLine($header);

        return empty($value) ? null : (string) $value;
    }

    /**
     * @return array<mixed>
     */
    public function grabParsedBody(): ?array
    {
        $parsed = $this->request->getParsedBody();

        if (is_object($parsed)) {
            $parsed = get_object_vars($parsed);
        }

        return $parsed;
    }
}
