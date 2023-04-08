<?php

final class ArrayInput
{
    private string $control;
    private array $input;

    /**
     * @param array<string, string> $input
     */
    public function __construct(
        array $input
    ) {
        $this->control = $input[1];
        $this->input = $input;
    }

    public function __toString(): string
    {
        switch($this->control) {
            case 'ping':
                return "+PONG\r\n";
            case 'echo':
                return "+{$this->input[3]}\r\n"; 
            default:
                return '';
        }
    }
}

