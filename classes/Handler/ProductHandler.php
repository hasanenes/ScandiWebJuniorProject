<?php

namespace Handler;

abstract class ProductHandler
{
    abstract public function getAllObjects();

    abstract public function addProduct($product);

    abstract public function getTypeName(): string;

    abstract public function deleteBySKU($sku);

    abstract public function validate($product): array;

    public function cleanData($data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        return htmlspecialchars($data);
    }

    public function isSkuDuplicate($product)
    {

        $findSKUBook = $this->db->conn
            ->prepare('SELECT * FROM book where sku=?');
        $findSKUBook->execute([$product->sku]);

        $findSKUDVD = $this->db->conn
            ->prepare('SELECT * FROM dvd where sku=?');
        $findSKUDVD->execute([$product->sku]);

        $findSKUFurniture = $this->db->conn
            ->prepare('SELECT * FROM furniture where sku=?');
        $findSKUFurniture->execute([$product->sku]);
        return $findSKUBook->rowCount() > 0
            || $findSKUDVD->rowCount() > 0
            || $findSKUFurniture->rowCount() > 0;
    }
}
