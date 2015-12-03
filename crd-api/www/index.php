<?php

use Phalcon\Mvc\Micro;

//phpinfo();

$app = new Micro();

// Autoload models
$loader = new \Phalcon\Loader();

$loader->registerDirs(
    array(
        __DIR__ . '/models/'
    )
)->register();

$customer = new Customers();
// Retrieves robots based on primary key
$app->get('/api/customers/{id:[0-9]+}',  array($customer, 'getById') );
$app->get('/api/customers/authenticate/{email}/{password}',  array($customer, 'authenticate') );


$product = new Products();
$app->get('/api/products/{id:[A-Za-z0-9]{8}}',  array($product, 'getById') );
$app->get('/api/products/prices/{id:[A-Za-z0-9]{8}}',  array($product, 'getPrices') );

$store = new Stores();
$app->get('/api/stores/{id:[0-9]+}',  array($store, 'getById') );
$app->get('/api/stores/search/location/{location}/{products}',  array($store, 'getNearestStoresByLocation') );
$app->get('/api/stores/search/coordinates/{coordinates}/{products}',  array($store, 'getNearestStoresByCoordinates') );
$app->get('/api/stores/reservation/{basketId}/{storeId}/{title}/{firstname}/{surname}/{phonenumber}',  array($store, 'makeReservation') );

$basket = new Basket();
$app->get('/api/basket/?{basketId}', array($basket, 'getJSON'));
$app->get('/api/basket/add/{product:[0-9]{8}}/{quantity:[0-9]{1,2}}/?{basketId}', array($basket, 'addProduct'));
$app->get('/api/basket/remove/{product:[0-9]{8}}/{quantity:[0-9]{1,2}}/{basketId}', array($basket, 'removeProduct'));

$app->handle();

?>
