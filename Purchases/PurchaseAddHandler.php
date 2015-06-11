<?php
/**
 * Created by PhpStorm.
 * User: Yarsoniy
 * Date: 10.06.2015
 * Time: 16:27
 */

namespace Purchases;


class PurchaseAddHandler extends RequestHandler{
    public function __construct(PurchaseAddRequest $request) {
        parent::__construct($request);
    }

    public function handle() {
        $requestData = $this->request->getData();
        $p = new Purchase();
        $p->setDescription($requestData->description);
        $p->setPrice($requestData->price);

        PurchaseStore::write($p);

        $purchases = PurchaseStore::getAll();
        $result = array();
        foreach ($purchases as $p) {
            $result[] = $p->toStdClass();
        }
        return new Response('loadRecords', $result);
    }
}