<?php
require '../vendor/autoload.php';

header('Access-Control-Allow-Origin:*');
header('Content-type:application/json;charset=utf-8');

use Phalcon\Mvc\Micro;

$app = new Micro();

//not found handler
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo 'This action has not been implemented yet but you never know...!';
});

// Autoload models
//$loader = new \Phalcon\Loader();

//$loader->registerNamespaces(array(
//'CrdApi\Models' => '../src/models'
//))->register();

try 
{
$customer = new \CrdApi\Models\Customers();
// Retrieves robots based on primary key
$app->get('/api/customers/{id:[0-9]+}',  array($customer, 'getById') );
$app->get('/api/customers/authenticate/{email}/{password}',  array($customer, 'authenticate') );


$product = new \CrdApi\Models\Products();
$app->get('/api/products/{id:[A-Za-z0-9]{8}}',  array($product, 'getById') );
$app->get('/api/products/prices/{id:[A-Za-z0-9]{8}}',  array($product, 'getPrices') );

$store = new \CrdApi\Models\Stores();
$app->get('/api/stores/{id:[0-9]+}',  array($store, 'getById') );
$app->get('/api/stores/search/location/{location}/{products}',  array($store, 'getNearestStoresByLocation') );
$app->get('/api/stores/search/coordinates/{coordinates}/{products}',  array($store, 'getNearestStoresByCoordinates') );
$app->post('/api/stores/reservation/{basketId}/{storeId}/{title}/{firstname}/{surname}/{phonenumber}',  array($store, 'makeReservation') );

$basket = new \CrdApi\Models\Basket();
$app->get('/api/basket/?{basketId}', array($basket, 'getJSON'));
$app->get('/api/basket/delete/{basketId}', array($basket, 'delete'));
$app->post('/api/basket/add/{product:[0-9]{8}}/{quantity:[0-9]{1,2}}/?{basketId}', array($basket, 'addProduct'));
$app->put('/api/basket/remove/{product:[0-9]{8}}/{quantity:[0-9]{1,2}}/{basketId}', array($basket, 'removeProduct'));

$app->handle();

}
catch (exception $s)
{
	echo $s->getMessage();
}

?>
