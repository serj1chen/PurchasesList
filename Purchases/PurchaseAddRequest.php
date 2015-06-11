<?php

/**
 * Created by PhpStorm.
 * User: Yarsoniy
 * Date: 10.06.2015
 * Time: 13:41
 */
namespace Purchases;

class PurchaseAddRequest extends Request{

    public function __construct($data) {
        $this->data = $data;
    }

    public function createHandler() {
        return new PurchaseAddHandler($this);
    }

}