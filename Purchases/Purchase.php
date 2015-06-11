<?php
/**
 * Created by PhpStorm.
 * User: Yarsoniy
 * Date: 10.06.2015
 * Time: 17:05
 */

namespace Purchases;

/**Об’єкти цього класу є відображенням покупок
 * Class Purchase
 * @package Purchases
 */
class Purchase {

    private $id = 0;

    /**Опис покупки
     * @var string
     */
    private $description = "";

    /**Ціна
     * @var float
     */
    private $price = 0.0;

    public function __construct(\stdClass $data = null) {
        if ($data) {
            $this->setId($data->id);
            $this->setDescription($data->description);
            $this->setPrice($data->price);
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = (int)$id;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = (string)$description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = (float)$price;
    }

    public function toStdClass() {
        $result = new \stdClass();
        $result->id = $this->id;
        $result->description = $this->description;
        $result->price = $this->price;

        return $result;
    }

    public function isNew() {
        return $this->id == 0;
    }
}