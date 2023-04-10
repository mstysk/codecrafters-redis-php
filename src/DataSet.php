<?php

declare(strict_types=1);

namespace Mstysk\RedisPhp;

use DateTimeImmutable;

final class DataSet
{
    private string $key;

    private string $value;

    private ?Expired $expired;

    public function __construct(
        string $key,
        string $value,
        ?Expired $expired = null
    ) {
        $this->key = $key;
        $this->value = $value;
        $this->expired = $expired;
    }

    public function exists(string $key): bool
    {
        if (!$this->expired) {
            return $this->key === $key;
        }
        $now = new DateTimeImmutable();
        if ($this->expired->less($now)) {
            return false;
        }
        return $this->key === $key;
    }

    public function get(): string
    {
        return $this->value;
    }
}
