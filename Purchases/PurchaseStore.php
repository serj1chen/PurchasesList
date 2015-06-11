<?php
/**
 * Created by PhpStorm.
 * User: Yarsoniy
 * Date: 10.06.2015
 * Time: 17:01
 */

namespace Purchases;

/** Являє собою сховище, у якому зберігаються створені покупки.
 * Відповідальний за додавання, видалення та редагування покупок
 * Class PurchaseStore
 * @package Purchases
 */
class PurchaseStore {

    private function __construct() {
    }

    public static function getAll() {
        $query = "
        SELECT
          id,
          description,
          price
        FROM purchases
        ";
        $dbResult = Database::getInstance()->fetchObjects($query);
        $result = array();
        foreach ($dbResult as $dbRow) {
            $result[] = new Purchase($dbRow);
        }
        return $result;
    }

    public static function read(Purchase $p) {
        if (!$p->isNew()) {
            $query = "
            SELECT
              id,
              description,
              price
            FROM purchases
            WHERE
              purchases.id = :id
            ";
            $params = array(':id' => $p->getId());
            $dbResult = Database::getInstance()->fetchObjects($query, $params);

            if ($dbResult) {
                $data = $dbResult[0];
                $p->setDescription($data->description);
                $p->setPrice($data->price);
            }
        }
    }

    public static function write(Purchase $p) {
        $params = array();
        if ($p->isNew()) {
            $query = "
            INSERT INTO purchases (description, price) VALUES
            (:description, :price)
            ";

        } else {
            $query = "
            UPDATE purchases
            SET
              description = :description,
              price = :price
            WHERE
              id = :id
            ";
            $params[':id'] = $p->getId();
        }
        $params[':description'] = $p->getDescription();
        $params[':price'] = $p->getPrice();

        Database::getInstance()->execute($query, $params);

        if ($p->isNew()) {
            $id = Database::getInstance()->lastInsertId();
            $p->setId($id);
        }
    }

    public static function remove(Purchase $p) {
        if (!$p->isNew()) {
            $query = "
            DELETE purchases
            FROM purchases
            WHERE
              purchases.id = :id
            ";
            $params = array(':id' => $p->getId());
            Database::getInstance()->execute($query, $params);

            $p->setId(0);
        }
    }
}