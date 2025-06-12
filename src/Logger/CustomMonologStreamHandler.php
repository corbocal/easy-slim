<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Logger;

use Corbocal\EasySlim\Enums\MonologLevelsEnum;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

/**
 * Custom class that format the log output before putting it in a file
 */
class CustomMonologStreamHandler extends StreamHandler
{
    protected const array KEYS_TO_FILTER = [
        "password"
        // TODO
    ];

    /**
     * Summary of __construct
     * @param string $file path to the file that will record
     * @param MonologLevelsEnum $minLevel
     * @param bool $bubble
     */
    public function __construct(
        string $file,
        MonologLevelsEnum $minLevel = MonologLevelsEnum::DEBUG,
        bool $bubble = true
    ) {
        parent::__construct(
            $file,
            $minLevel->value,
            $bubble
        );
        $this->pushProcessor(
            function ($entry) {
                // TODO EXPUNGE passwords and other secrets
                return $entry;
            }
        );
    }

    public function getFormatter(): FormatterInterface
    {
        return new LineFormatter('%message%' . chr(0x0A));
    }

    // public function filterParameters(mixed $data)
    // {
    //     if (empty($data)) {
    //         return $data;
    //     }

    //     $result = $data;
    //     if (is_array($data)) {
    //         foreach ($data as $key => $value) {
    //             if (is_string($key) && in_array(strtolower($key), self::KEYS_TO_FILTER)) {
    //                 $result[$key] = '***redacted***';
    //             }
    //             if (is_array($result[$key])) {
    //                 $result[$key] = $this->filterParameters($result[$key]);
    //             }
    //         }
    //     }

    //     return $result;
    // }
}
