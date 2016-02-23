<?php

namespace CrdApi\tests\Models;

use \CrdApi\Models\Basket;

class BasketTest extends \PHPUnit_Framework_TestCase
{
    public function testProductcanBeAddedtoBasket()
    {
        $basket = new Basket();
        $this->assertInstanceOf('\CrdApi\Models\Basket', $basket);
    }
}
