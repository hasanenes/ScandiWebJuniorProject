<?php

use Handler\BookHandler;
use Handler\DvdHandler;
use Handler\FurnitureHandler;

class ProductHandlerFactory
{
    public static function makeProductHandler($productType, $db): FurnitureHandler|BookHandler|DvdHandler
    {
        $productHandler = null;
        if ($productType === 'Book') {
            $productHandler = new Handler\BookHandler($db);
        }

        if ($productType === 'Dvd') {
            $productHandler = new Handler\DvdHandler($db);
        }

        if ($productType === 'Furniture') {
            $productHandler = new Handler\FurnitureHandler(
                $db
            );
        }

        if ($productHandler == null) {
            throw new Error("Unrecognized Product Type: $productType");
        }

        return $productHandler;
    }
}
