<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Traits;

trait HttpHeadersTrait
{
    public const string HEADER_HTTP_REFERER = "HTTP_REFERER";
    public const string HEADER_HTTP_X_FORWARDED_FOR = "HTTP_X_FORWARDED_FOR";
    public const string HEADER_HTTP_X_FORWARDED_HOST = "HTTP_X_FORWARDED_HOST";
    public const string HEADER_REQUEST_TIME_FLOAT = "REQUEST_TIME_FLOAT";
    public const string HEADER_X_REQUEST_ID = "X-REQUEST-ID";
}
