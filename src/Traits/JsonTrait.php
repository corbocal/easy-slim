<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Traits;

trait JsonTrait
{
    public function jsonEncode(
        mixed $value,
        int $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
        int $depth = 512
    ): string {
        $depth = \min(\max($depth, 1), PHP_INT_MAX);
        return json_encode(
            $value,
            $flags,
            $depth
        ) ?: "";
    }

    public function jsonEncodePrettyPrintResponse(
        mixed $value,
        int $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT,
        int $depth = 512
    ): string {
        $depth = \min(\max($depth, 1), PHP_INT_MAX);
        return json_encode(
            $value,
            $flags,
            $depth
        ) ?: "";
    }
}
