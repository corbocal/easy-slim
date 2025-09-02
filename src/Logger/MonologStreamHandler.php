<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Logger;

use Corbocal\EasySlim\Enums\Logger\PsrLevelsEnum;
use Corbocal\EasySlim\Traits\JsonTrait;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\LogRecord;

/**
 * Custom class that format the log output before putting it in a file
 */
class MonologStreamHandler extends StreamHandler
{
    use JsonTrait;

    private const string SECRETS_REGEX = '/(pass(word)?|mdp|pwd|mot.?de.?passe|secret|s3cr3t|token|key|bearer)/i';

    /**
     * @param string $file path to the file where the log message will be written
     * @param PsrLevelsEnum $level
     * @param bool $bubble
     */
    public function __construct(
        string $file,
        PsrLevelsEnum $level,
        bool $bubble = true
    ) {
        parent::__construct(
            $file,
            $level->value,
            $bubble
        );
        $this->pushProcessor(
            function ($entry) {
                /** @var array<string,mixed> */
                $originalMessageArray = json_decode($entry->message, true) ?: [];
                /** @var array<string,mixed> */
                $originalPayload = $originalMessageArray['payload'] ?? [];
                if (!empty($originalPayload)) {
                    $alteredPayload = self::expungeSecretsRecursively($originalPayload);
                    /** @var array<string,mixed> */
                    $newMessageArray = array_merge($originalMessageArray, ['payload' => $alteredPayload]);
                } else {
                    $newMessageArray = $originalMessageArray;
                }

                $expungedEntry = new LogRecord(
                    $entry->datetime,
                    $entry->channel,
                    $entry->level,
                    $this->jsonEncode($newMessageArray),
                    $entry->context,
                    $entry->extra,
                    $entry->formatted
                );

                return $expungedEntry;
            }
        );
    }

    public function getFormatter(): FormatterInterface
    {
        return new LineFormatter('%message%' . chr(0x0A));
    }

    /**
     * @param array<mixed> $data The data to expunge of secrets
     * @return array<mixed> The expunged data
     */
    private static function expungeSecretsRecursively(array $data): array
    {
        if ($data !== []) {
            foreach ($data as $key => &$value) {
                if (is_array($value)) {
                    $value = self::expungeSecretsRecursively($value);
                }
                if (is_string($key) && preg_match(self::SECRETS_REGEX, $key)) {
                    $data[$key] = "**expunged**";
                }
            }
        }
        return $data;
    }
}
