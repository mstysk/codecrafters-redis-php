<?php

declare(strict_types=1);

namespace Mstysk\RedisPhp;

use DateTimeInterface;

final class DataSet
{
    private string $key;

    private string $value;

    private DateTimeInterface $expired;

    public function __construct(
        string $key,
        string $value
    ) {
        $this->key = $key;
        $this->value = $value;
    }

    public function exists(string $key): bool
    {
        return $this->key === $key;
    }

    public function get(): string
    {
        return $this->value;
    }
}
