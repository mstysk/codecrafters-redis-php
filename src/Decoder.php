<?php

declare(strict_types=1);

namespace Mstysk\RedisPhp;

final class Decoder 
{
    private const CRLF = "\r\n";

    private Storage $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }
    /**
     * @return ArrayInput|array
     */
    public function decode(string $str)
    {
        $input = array_filter(explode(self::CRLF, $str));
        $head = array_shift($input);
        if(is_null($head)) {
            return ;
        }
        switch(substr($head, 0, 1)) {
            case '*':
                return new ArrayInput($input, $this->storage);
            case '-':
                echo 'error';
                break;
            case '+':
                echo 'string';
                break;
            case ':':
                echo 'integer';
                break;
            case '$':
                echo 'bulk string';
                break;
        }
        return array_chunk($input, 2);
    }
}
