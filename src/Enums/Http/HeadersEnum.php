<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Enums\Http;

enum HeadersEnum: string
{
    case REQUEST_TIME_FLOAT = "REQUEST_TIME_FLOAT";
    case X_REQUEST_MICROTIME = "X-REQUEST-MICROTIME";
    case REFERER = "REFERER";
    case X_FORWARDED_HOST = "X-FORWARDED-HOST";
    case X_FORWARDED_FOR = "X-FORWARDED-FOR";
    case X_REAL_IP = "X-REAL-IP";
    case X_REQUEST_ID = "X-REQUEST-ID";
    case USER_AGENT = "USER-AGENT";

    /**
     * @return array<string>
     */
    public static function allForIp(): array
    {
        return [
            self::X_REAL_IP->value,
            self::X_FORWARDED_FOR->value,
        ];
    }

    /**
     * @return array<string>
     */
    public static function allForReferer(): array
    {
        return [
            self::REFERER->value,
            self::X_FORWARDED_HOST->value,
        ];
    }
}
