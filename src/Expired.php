<?php

declare(strict_types=1);

namespace Mstysk\RedisPhp;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;

final class Expired
{
    private string $type;

    private string $value;

    private DateTimeInterface $expiredTime;

    public function __construct(
        string $type,
        string $value
    ) {
        switch($type) {
            case 'px': // millseconds
                $modifier = "{$value} ms";
            break;
            case 'ex': // seconds
                $modifier = "{$value} s";
            break;
            default:
                throw new Exception('Expired Type is not defined');
            break;
        }
        $this->expiredTime = (new DateTimeImmutable())
            ->modify($modifier);
    }

    public function less(DateTimeInterface $date): bool
    {
        return $date >= $this->expiredTime;
    }

    /** @param array<string, string> $array */
    public static function createFromInputArray(
        array $array
    ): ?self
    {
        if (isset($array[7]) && isset($array[9])) {
            return new self($array[7], $array[9]);
        }
        return null;
    }
}
