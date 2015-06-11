<?php

/**
 * Created by PhpStorm.
 * User: Yarsoniy
 * Date: 10.06.2015
 * Time: 13:41
 */
namespace Purchases;

class ConnectionRequest extends Request{

    public function __construct() {

    }

    public function createHandler() {
        return new ConnectionHandler($this);
    }

}