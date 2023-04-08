<?php

declare(strict_types=1);

namespace Mstysk\RedisPhp;

final class Storage
{
    private array $data;

    public function set(string $key, string $value): void
    {
        $this->data[$key] = $value;
    }

    public function get(string $key): ?string
    {
        if($this->data[$key]) {
            return $this->data[$key];
        }
        return null;
    }
}
