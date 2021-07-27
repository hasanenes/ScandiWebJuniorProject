<?php

namespace Handler;

use PDO;

class BookHandler extends ProductHandler
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
        $createBookTable = "CREATE TABLE if not exists book(
                    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
                    sku varchar(256) NOT NULL UNIQUE,
                    name varchar(256) NOT NULL,
                    price decimal NOT NULL,
                    weight decimal NOT NULL
                     )";

        $this->db->conn->query($createBookTable);
    }

    public function addProduct($product)
    {
        $insertValues = [$product->sku, $product->price, $product->name, $product->weight];
        $cleanedInsertValues = array();

        foreach ($insertValues as $insertValue) {
            $cleanedInsertValues[] = $this->cleanData($insertValue);
        }

        $insertQuery = "INSERT INTO book( sku, price, name, weight) VALUES(?,?,?,?)";
        $queryToExecute = $this->db->conn->prepare($insertQuery);
        $queryToExecute->execute($cleanedInsertValues);


    }


    public function getAllObjects()
    {
        $rawResult = $this->db->conn->query('SELECT * FROM `book`');
        return $rawResult->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Product\Book", [0, 0, 0, 0]);
    }

    public function getTypeName(): string
    {
        return 'Book';
    }

    public function deleteBySku($sku)
    {
        $preparedStatement = $this->db->conn->prepare('DELETE FROM `book` where sku=?');
        $preparedStatement->execute([$sku]);
    }

    public function validate($product): array
    {
        $plsProvideDataOfIndicatedType = 'Please, provide the data of indicated type';

        $errorArray = array();
        if (empty($product->name)) {
            $errorArray["nameErr"] = "Please enter a product name";
        }

        if (empty($product->price)) {
            $errorArray["priceErr"] = "Please enter a product price";
        } elseif (!is_numeric($product->price)) {
            $errorArray["priceErr"] = $plsProvideDataOfIndicatedType;
        }
        if (empty($product->sku)) {
            $errorArray["skuErr"] = "Please enter a sku";
        } else {


            if (parent::isSkuDuplicate($product)) {
                $errorArray['skuErr'] = "Duplicate SKU exists: $product->sku";
            }
        }

        if (empty($product->weight)) {
            $errorArray["weightErr"] = "Please enter a weight";
        } elseif (!is_numeric($product->weight)) {
            $errorArray["weightErr"] = $plsProvideDataOfIndicatedType;
        }

        return $errorArray;
    }
}
