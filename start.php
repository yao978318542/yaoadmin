<?php
$sendMsg=json_encode(["parameters"=>[
    "port"=>10000,
    "version"=>"3.1",
]]);
$handle = stream_socket_client("udp://192.168.1.255:10000", $errno, $errstr);
if( !$handle ){
    die("ERROR: {$errno} - {$errstr}\n");
}
fwrite($handle, '{"parameters":{"port":10000,"sessionId":"84bfe87b-e0b7-4b05-95e0-0817617dc2bb","version":"3.1"}}');
$result = fread($handle, 1024);
fclose($handle);
socket_create();