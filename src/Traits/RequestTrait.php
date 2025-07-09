<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Traits;

use Corbocal\EasySlim\Enums\Http\HeadersEnum;
use Psr\Http\Message\ServerRequestInterface as Request;

trait RequestTrait
{
    protected Request $request;

    /**
     * Will return the number of milliseconds ellapsed between the moment the request entered the first middleware,
     *
     * @return int
     */
    public function calculateRequestDuration(): int
    {
        (float) $microtime = $this->request->getHeaderLine(HeadersEnum::X_REQUEST_MICROTIME->value);
        $now = microtime(true);
        $difference = $now - (float) $microtime;
        return (int) ($difference * 1000);
    }
}
