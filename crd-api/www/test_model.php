<?php

include('models/Customers.php');
include('models/Products.php');
include('models/Stores.php');

#$oCustomer = new Customers();

#echo $oCustomer->getById(78352751);
#echo $oCustomer->authenticate('jm.pulvar@gmail.com', '3725fb46f63743b98d2a7cf44feb7e92');
#echo $oCustomer->authenticate('jm.pulvar@gmail.com', '3725fb46f63743b98d2a7cf44f7e92');

#$oProduct = new products();

#echo $oProduct->getById('10133883');
#echo $oProduct->getPrices('10133883');

$oStore = new stores();

echo $oStore->getById(200);

?>
