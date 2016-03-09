<?php

namespace CrdApi\tests\Models;

use \CrdApi\Models\Basket;

class BasketTest extends \PHPUnit_Framework_TestCase
{
    private $basket;

    public function setUp()
    {
        $this->basket = new Basket();
    }

    public function teardown()
    {
        $this->basket->delete();
    }

    public function testEmptyBasket()
    {
        $this->checkUniqueProductsCountEquals(0);
    }

    /**
     * @dataProvider provider
     */
    public function testProductCanBeAddedtoEmptyBasket($product, $quantity)
    {
        //verifying basket is empty
        $this->testEmptyBasket();
        //adding product
        $this->basket->addProduct($product, $quantity);

        $this->checkBasketHasProducts(array($product['productId']));
        $this->checkUniqueProductsCountEquals(1);
        $this->checkTotalProductsCountEquals($quantity);
        $this->checkIATSubtotalEquals($product['priceIAT'] * $quantity);
        $this->checkEATSubtotalEquals($product['priceEAT'] * $quantity);
    }

    /**
     * @dataProvider provider
     */
    public function testProductCanBeAddedMultipleTimes($product, $quantity)
    {
        //verifying basket is empty
        $this->testEmptyBasket();
        //adding twice product
        $this->basket->addProduct($product, $quantity);
        $this->basket->addProduct($product, $quantity);

        $this->checkUniqueProductsCountEquals(1);
        $this->checkTotalProductsCountEquals($quantity * 2);
        $this->checkIATSubtotalEquals($product['priceIAT'] * $quantity * 2);
        $this->checkEATSubtotalEquals($product['priceEAT'] * $quantity * 2);
    }

    /**
     * @dataProvider provider
     */
    public function testDifferentProductsCanBeAddedToBasket($product1, $quantity1, $product2, $quantity2)
    {
        //verifying basket is empty
        $this->testEmptyBasket();
        //adding twice product
        $this->basket->addProduct($product1, $quantity1);
        $this->basket->addProduct($product2, $quantity2);

        $this->checkBasketHasProducts(array($product1['productId'], $product2['productId']));
        $this->checkUniqueProductsCountEquals(2);
        $this->checkTotalProductsCountEquals($quantity1 + $quantity2);
        $this->checkIATSubtotalEquals($product1['priceIAT'] * $quantity1 + $product2['priceIAT']* $quantity2);
        $this->checkEATSubtotalEquals($product1['priceEAT'] * $quantity1 + $product2['priceEAT']* $quantity2);
    }

    /**
     * @dataProvider provider
     */
    public function testProductCanBeRemovedFromSingleBasket($product, $quantity)
    {
        //verifying basket is empty
        $this->testEmptyBasket();
        //adding product
        $this->basket->addProduct($product, $quantity);
        $this->basket->removeProduct($product['productId'], $quantity);

        $this->checkUniqueProductsCountEquals(0);
        $this->checkTotalProductsCountEquals(0);
        $this->checkIATSubtotalEquals(0);
        $this->checkEATSubtotalEquals(0);
    }

    /**
     * @dataProvider provider
     */
    public function testProductCanBeRemovedFromMultipleBasket($product1, $quantity1, $product2, $quantity2)
    {
        //verifying basket is empty
        $this->testEmptyBasket();
        //adding product
        $this->basket->addProduct($product1, $quantity1);
        $this->basket->addProduct($product2, $quantity2);
        $this->basket->removeProduct($product1['productId'], $quantity1);

        $this->checkBasketHasProducts(array($product2['productId']));
        $this->checkUniqueProductsCountEquals(1);
        $this->checkTotalProductsCountEquals($quantity2);
        $this->checkIATSubtotalEquals($product2['priceIAT'] * $quantity2);
        $this->checkEATSubtotalEquals($product2['priceEAT'] * $quantity2);
    }

    public function checkBasketHasProducts($productIds)
    {
        $this->assertTrue($this->basket->hasProducts($productIds));
    }

    public function checkUniqueProductsCountEquals($quantity)
    {
        $this->assertEquals($this->basket->getUniqueProductsCount(), $quantity);
    }

    public function checkTotalProductsCountEquals($quantity)
    {
        $this->assertEquals($this->basket->getTotalProductsCount(), $quantity);
    }

    public function checkIATSubtotalEquals($IATTotal)
    {
        $this->assertEquals($this->basket->getIATSubTotal(), $IATTotal);
    }

    public function checkEATSubtotalEquals($EATTotal)
    {
        $this->assertEquals($this->basket->getEATSubTotal(), $EATTotal);
    }

    public function getJSONResult()
    {
        return json_decode($this->basket->getJSON(true));
    }

    public function provider()
    {
        return array(array(array(
            'productId'        => '10133883',
            'title'     => 'Impressions VTT702 4-Slice Toaster - Vanilla Cream',
            'image'     => 'http://brain-images.cdn.dixons.com/3/8/10133883/t_10133883.jpg',
            'priceIAT'  => 29.15833,
            'priceEAT'  => 34.99,
            'VAT'       => 5.831666
        ), 2,
        array(
            'productId' => '10135119',
            'title' => 'Power III TDA3020GB Steam Iron - Black',
            'priceEAT' => 49.99165,
            'priceIAT' => 59.99,
            'VAT' => 9.99833,
            'image' => 'http://brain-images.cdn.dixons.com/9/1/10135119/t_10135119.jpg'
            ), 3)
        );
    }
}
