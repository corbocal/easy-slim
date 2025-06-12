<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Enums;

enum HttpHeadersEnum: string
{
    case HTTP_REFERER = "HTTP_REFERER";
    case HTTP_X_FORWARDED_FOR = "HTTP_X_FORWARDED_FOR";
    case HTTP_X_FORWARDED_HOST = "HTTP_X_FORWARDED_HOST";
    case REQUEST_TIME_FLOAT = "REQUEST_TIME_FLOAT";
    case X_REQUEST_ID = "X-REQUEST-ID";

    /**
     * @return array<int>
     */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}