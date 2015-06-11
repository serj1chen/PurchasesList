<?php
/**
 * Created by PhpStorm.
 * User: Yarsoniy
 * Date: 10.06.2015
 * Time: 16:27
 */

namespace Purchases;


class ConnectionHandler extends RequestHandler{
    public function __construct(ConnectionRequest $request) {
        parent::__construct($request);
    }

    public function handle() {
        $purchases = PurchaseStore::getAll();

        $result = array();
        foreach ($purchases as $p) {
            $result[] = $p->toStdClass();
        }
        return new Response('loadRecords', $result);
    }
}