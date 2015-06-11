<?php

/**
 * Created by PhpStorm.
 * User: Yarsoniy
 * Date: 10.06.2015
 * Time: 13:34
 */
namespace Purchases;

/** Інкапсулює дані запиту клієнта і надає доступ до
 * обробника відповідного запиту
 * Class Request
 * @package Purchases
 */
abstract class Request {
    protected $data;

    abstract function createHandler();

    public function getData() {
        return $this->data;
    }

    /**Метод аналізує вхідні дані від клієнта і повертає відповідний об’єкт Request
     * @param $rawRequest
     * @return null|PurchaseAddRequest|PurchaseRemoveRequest|PurchaseUpdateRequest
     */
    public static function parse($rawRequest) {
        $details = json_decode($rawRequest);

        $request = null;
        if (property_exists($details, 'comand')) {
            $comand = $details->comand;

            switch ($comand) {
                case 'updatePurchase':
                    $request = new PurchaseUpdateRequest($details->data);
                    break;
                case 'addPurchase':
                    $request = new PurchaseAddRequest($details->data);
                    break;
                case 'removePurchase':
                    $request = new PurchaseRemoveRequest($details->data);
                    break;
            }
        }

        return $request;
    }
}