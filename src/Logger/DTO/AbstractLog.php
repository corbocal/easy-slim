<?php

declare(strict_types=1);

namespace Corbocal\EasySlim\Logger\DTO;

use Corbocal\EasySlim\Traits\JsonTrait;

abstract readonly class AbstractLog implements \Stringable
{
    use JsonTrait;

    public ?string $date;

    public function __construct(
        public ?string $level,
        public ?string $requestId,
    ) {
        $this->date = new \DateTimeImmutable('now')->format("Y-m-d\\TH:i:s.suP");
    }

    /**
     * @return array<mixed>
     */
    public function __serialize(): array
    {
        return get_object_vars($this);
    }

    public function __tostring(): string
    {
        return self::jsonEncode($this->__serialize());
    }
}
