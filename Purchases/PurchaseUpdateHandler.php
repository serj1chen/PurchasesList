<?php
/**
 * Created by PhpStorm.
 * User: Yarsoniy
 * Date: 10.06.2015
 * Time: 16:27
 */

namespace Purchases;


class PurchaseUpdateHandler extends RequestHandler{
    public function __construct(PurchaseUpdateRequest $request) {
        parent::__construct($request);
    }

    public function handle() {
        $requestData = $this->request->getData();
        $p = new Purchase();
        $p->setId($requestData->id);
        $p->setDescription($requestData->description);
        $p->setPrice($requestData->price);

        if (!$p->getId()) {
            return new Response();
        }

        PurchaseStore::write($p);

        $purchases = PurchaseStore::getAll();
        $result = array();
        foreach ($purchases as $p) {
            $result[] = $p->toStdClass();
        }
        return new Response('loadRecords', $result);
    }
}