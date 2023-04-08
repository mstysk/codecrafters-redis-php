<?php

declare(strict_types=1);

namespace Mstysk\RedisPhp;

final class ArrayInput
{
    private string $control;
    private array $input;

    /**
     * @param array<string, string> $input
     */
    public function __construct(
        array $input,
        Storage $storage
    ) {
        $this->control = $input[1];
        $this->input = $input;
        $this->storage = $storage;
    }

    public function __toString(): string
    {
        switch($this->control) {
            case 'ping':
                return "+PONG\r\n";
            case 'echo':
                return "+{$this->input[3]}\r\n"; 
            case 'set':
                $key = $this->input[3];
                $value = $this->input[5];
                $this->storage->set($key, $value);
                return "+OK\r\n";
            case 'get':
                $key = $this->input[3];
                return "+{$this->storage->get($key)}\r\n";
            default:
                return '';
        }
    }
}
