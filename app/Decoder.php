<?php

declare(strict_types=1);

final class Decoder 
{
    private const CRLF = "\r\n";

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
                return new ArrayInput($input);
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
