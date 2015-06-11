<?php
/**
 * Created by PhpStorm.
 * User: Yarsoniy
 * Date: 09.06.2015
 * Time: 21:55
 */
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Purchases\ApplicationServer;

require_once __DIR__ . '/vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ApplicationServer()
        )
    ),
    8080);

echo "Server is running!\n";
$server->run();
