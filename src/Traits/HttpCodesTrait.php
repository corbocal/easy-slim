<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Traits;

trait HttpCodesTrait
{
    public const int HTTP_OK = 200;
    public const int HTTP_CREATED = 201;
    public const int HTTP_ACCEPTED = 202;
    public const int HTTP_NO_CONTENT = 204;
    public const int HTTP_RESET_CONTENT = 205;
    public const int HTTP_PARTIAL_CONTENT = 206;
    public const int HTTP_MOVED_PERMANENTLY = 301;
    public const int HTTP_FOUND = 302;
    public const int HTTP_SEE_OTHER = 303;
    public const int HTTP_NOT_MODIFIED = 304;
    public const int HTTP_TEMPORARY_REDIRECT = 307;
    public const int HTTP_PERMANENT_REDIRECT = 308;
    public const int HTTP_BAD_REQUEST = 400;
    public const int HTTP_UNAUTHORIZED = 401;
    public const int HTTP_PAYMENT_REQUIRED = 402;
    public const int HTTP_FORBIDDEN = 403;
    public const int HTTP_NOT_FOUND = 404;
    public const int HTTP_METHOD_NOT_ALLOWED = 405;
    public const int HTTP_NOT_ACCEPTABLE = 406;
    public const int HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
    public const int HTTP_REQUEST_TIMEOUT = 408;
    public const int HTTP_CONFLICT = 409;
    public const int HTTP_GONE = 410;
    public const int HTTP_LENGTH_REQUIRED = 411;
    public const int HTTP_PRECONDITION_FAILED = 412;
    public const int HTTP_PAYLOAD_TOO_LARGE = 413;
    public const int HTTP_URI_TOO_LONG = 414;
    public const int HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
    public const int HTTP_RANGE_NOT_SATISFIABLE = 416;
    public const int HTTP_EXPECTATION_FAILED = 417;
    public const int HTTP_IM_A_TEAPOT = 418;
    public const int HTTP_MISDIRECTED_REQUEST = 421;
    public const int HTTP_UPGRADE_REQUIRED = 426;
    public const int HTTP_PRECONDITION_REQUIRED = 428;
    public const int HTTP_TOO_MANY_REQUESTS = 429;
    public const int HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    public const int HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451;
    public const int HTTP_INTERNAL_SERVER_ERROR = 500;
    public const int HTTP_NOT_IMPLEMENTED = 501;
    public const int HTTP_BAD_GATEWAY = 502;
    public const int HTTP_SERVICE_UNAVAILABLE = 503;
    public const int HTTP_GATEWAY_TIMEOUT = 504;
    public const int HTTP_VERSION_NOT_SUPPORTED = 505;
    public const int HTTP_VARIANT_ALSO_NEGOTIATES = 506;
    public const int HTTP_INSUFFICIENT_STORAGE = 507;
    public const int HTTP_LOOP_DETECTED = 508;
    public const int HTTP_NOT_EXTENDED = 510;
    public const int HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;

    /**
     * @var array<int>
     */
    public const array ALL_HTTP_CODES = [
        self::HTTP_OK,
        self::HTTP_CREATED,
        self::HTTP_ACCEPTED,
        self::HTTP_NO_CONTENT,
        self::HTTP_RESET_CONTENT,
        self::HTTP_PARTIAL_CONTENT,
        self::HTTP_MOVED_PERMANENTLY,
        self::HTTP_FOUND,
        self::HTTP_SEE_OTHER,
        self::HTTP_NOT_MODIFIED,
        self::HTTP_TEMPORARY_REDIRECT,
        self::HTTP_PERMANENT_REDIRECT,
        self::HTTP_BAD_REQUEST,
        self::HTTP_UNAUTHORIZED,
        self::HTTP_PAYMENT_REQUIRED,
        self::HTTP_FORBIDDEN,
        self::HTTP_NOT_FOUND,
        self::HTTP_METHOD_NOT_ALLOWED,
        self::HTTP_NOT_ACCEPTABLE,
        self::HTTP_PROXY_AUTHENTICATION_REQUIRED,
        self::HTTP_REQUEST_TIMEOUT,
        self::HTTP_CONFLICT,
        self::HTTP_GONE,
        self::HTTP_LENGTH_REQUIRED,
        self::HTTP_PRECONDITION_FAILED,
        self::HTTP_PAYLOAD_TOO_LARGE,
        self::HTTP_URI_TOO_LONG,
        self::HTTP_UNSUPPORTED_MEDIA_TYPE,
        self::HTTP_RANGE_NOT_SATISFIABLE,
        self::HTTP_EXPECTATION_FAILED,
        self::HTTP_IM_A_TEAPOT,
        self::HTTP_MISDIRECTED_REQUEST,
        self::HTTP_UPGRADE_REQUIRED,
        self::HTTP_PRECONDITION_REQUIRED,
        self::HTTP_TOO_MANY_REQUESTS,
        self::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE,
        self::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS,
        self::HTTP_INTERNAL_SERVER_ERROR,
        self::HTTP_NOT_IMPLEMENTED,
        self::HTTP_BAD_GATEWAY,
        self::HTTP_SERVICE_UNAVAILABLE,
        self::HTTP_GATEWAY_TIMEOUT,
        self::HTTP_VERSION_NOT_SUPPORTED,
        self::HTTP_VARIANT_ALSO_NEGOTIATES,
        self::HTTP_INSUFFICIENT_STORAGE,
        self::HTTP_LOOP_DETECTED,
        self::HTTP_NOT_EXTENDED,
        self::HTTP_NETWORK_AUTHENTICATION_REQUIRED,
    ];

    public static function isHttpCode(int $code): bool
    {
        return in_array($code, self::ALL_HTTP_CODES);
    }

    public static function isSuccess(int $code): bool
    {
        return $code >= 200 && $code < 300;
    }

    public static function isRedirect(int $code): bool
    {
        return $code >= 300 && $code < 400;
    }

    public static function isClientError(int $code): bool
    {
        return $code >= 400 && $code < 500;
    }

    public static function isServerError(int $code): bool
    {
        return $code >= 500 && $code < 600;
    }

    public static function isError(int $code): bool
    {
        return self::isClientError($code) || self::isServerError($code);
    }

    public static function isInformational(int $code): bool
    {
        return $code >= 100 && $code < 200;
    }
}
