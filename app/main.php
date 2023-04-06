<?php
error_reporting(E_ALL);

require_once __DIR__ . '/ArrayInput.php';

// You can use print statements as follows for debugging, they'll be visible when running tests.
//echo "Logs from your program will appear here";

// Uncomment this to pass the first stage
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($sock, SOL_SOCKET, SO_REUSEPORT, 1);
socket_bind($sock, "localhost", 6379);
socket_listen($sock, 5);

$clients[] = $sock;
$write = null;
$expect = null;
$second = 0;

$resp = new Resp();

while(true) {
    $read = $clients;
    $selected  = socket_select($read, $write, $expect, $second);
    if ($selected === false) {
        echo 'socket select is failed.';
        break;
    }
    if ($selected  <= 0 ){
        continue;
    }
    if(in_array($sock, $read)) {
        $newSocket = socket_accept($sock);
        if ($newSocket === false) {
            echo 'new socket accept failed.';
            break;
        }
        $clients[] = $newSocket;
        echo "There are " . (count($clients) - 1)." clients";
        $key = array_search($sock, $read);
        unset($read[$key]);
    }
    foreach($read as $readSock) {
        $mess = socket_read($readSock, 2048);
        if ($mess === false) {
            $key = array_search($readSock, $clients);
            unset($clients[$key]);
            echo "client disconnected.\n";
            continue;
        }
        $response = $resp->decode($mess);
        if($response instanceof ArrayInput) {
            socket_write($readSock, $response, strlen($response));
        }
    }
}
socket_close($sock);


class Resp
{
    private const CRLF = "\r\n";

    /**
     * @return ArrayInput|array
     */
    public function decode(string $str)
    {
        $input = array_filter(explode(self::CRLF, $str));
        $head = array_shift($input);
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
