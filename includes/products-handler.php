<?php
$databaseHandler = new DatabaseHandler();
$productHandlers = [
    ProductHandlerFactory::makeProductHandler('Book', $databaseHandler),
    ProductHandlerFactory::makeProductHandler('Furniture', $databaseHandler),
    ProductHandlerFactory::makeProductHandler('Dvd', $databaseHandler),
];
$productsHandler = new ProductsHandler($productHandlers);
