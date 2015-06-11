<?php
/**
 * Created by PhpStorm.
 * User: Yarsoniy
 * Date: 10.06.2015
 * Time: 14:17
 */

namespace Purchases;


class Response {
    private $header = '';
    private $body;

    public function __construct($header = '', $data = null) {
        $this->header = $header;
        $this->body = $data;
    }

    public function getClientResponse() {
        $object = new \stdClass();
        $object->header = $this->header;
        $object->body = $this->body;

        $result = json_encode($object);
        return $result;
    }

    public function isEmpty() {
        return !$this->header;
    }
}