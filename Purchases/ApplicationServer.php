<?php

/**
 * Created by PhpStorm.
 * User: Yarsoniy
 * Date: 09.06.2015
 * Time: 20:44
 */

namespace Purchases;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ApplicationServer implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    /**Викликається при новому вхідному з’єднанні
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";

        $request = new ConnectionRequest();
        $response = $request->createHandler()->handle();

        $conn->send($response->getClientResponse());
    }

    /**Викликається, коли один з клієнтів присилає на сервер
     * повідомлення
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg) {
        $request = Request::parse($msg);
        if ($request) {
            $response = $request->createHandler()->handle();
            $responseJson = $response->getClientResponse();
            foreach ($this->clients as $client) {
                $client->send($responseJson);
            }
        }
    }

    /**Викликається, коли клієнт від’єднується
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
