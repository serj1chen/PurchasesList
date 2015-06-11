<?php

/**
 * Created by PhpStorm.
 * User: Yarsoniy
 * Date: 10.06.2015
 * Time: 13:34
 */
namespace Purchases;

/**Відповідає за логіку обробки об’єктів типу Request
 * Class RequestHandler
 * @package Purchases
 */
abstract class RequestHandler
{
    protected $request;

    abstract public function handle();

    public function __construct(Request $request) {
        $this->request = $request;
    }
}