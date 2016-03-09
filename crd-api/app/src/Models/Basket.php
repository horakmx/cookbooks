<?php

use Phalcon\Mvc\Model;

namespace CrdApi\Models;

class Basket
{
    private $basketId;
    private $products;
    private $cache;

    private function getCache()
    {
        $frontCache = new \Phalcon\Cache\Frontend\Data(array("lifetime" => 3600));
        $this->cache = new \Phalcon\Cache\Backend\Memcache($frontCache, array(
                 'host' => '127.0.0.1',
                 'port' => 11211,
                 'persistent' => false
        ));
    }

    public function save()
    {
        $this->getCache();
        $this->cache->save($this->basketId, $this->products);
    }

    public function load($basketId = null)
    {
        $this->setBasketId($basketId);

        $this->getCache();

        if (false === empty($this->basketId)) {
            $this->products = $this->cache->get($this->basketId);
        }
    }

    public function delete($basketId = null)
    {
        if (true === is_null($this->basketId)) {
            if ($basketId !== 0) {
                $this->basketId = $basketId;
            }
        }

        $this->getCache();
        if (true === $this->cache->exists($this->basketId)) {
            $this->cache->delete($this->basketId);
        }
        $this->products = null;
        $this->getJSON();
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function getUniqueProductsCount()
    {
        return count($this->products);
    }

    public function getTotalProductsCount()
    {
        $totalQuantity = 0;
        foreach ($this->products as $product) {
            $totalQuantity += $product['quantity'];
        }

        return $totalQuantity;
    }

    private function setBasketId($basketId)
    {
        if (true === is_null($basketId)) {
            if (true === is_null($this->basketId)) {
                $basketId = uniqid(true);
                $this->basketId = $basketId;
            }
        } else {
            $this->basketId = $basketId;
        }
    }

    public function hasProducts(array $productIds)
    {
        return array_intersect($productIds, array_keys($this->products)) === $productIds;
    }

    public function addProduct(array $product, $quantity, $basketId = null)
    {
        $this->setBasketId($basketId);
        $this->load();

        if (false === isset($this->products[$product['productId']])) {
            $this->products[$product['productId']]     = array(
                                                    'productId'        => $product['productId'],
                                                    'title'            => $product['title'],
                                                    'image'            => $product['image'],
                                                    'quantity'         => (int) $quantity,
                                                    'unitPriceIAT'     => $product['priceIAT'],
                                                    'unitPriceEAT'     => $product['priceEAT'],
                                                    'totalVAT'         => $quantity * $product['VAT'],
                                                    'totalPriceIAT'    => $quantity * $product['priceIAT'],
                                                    'totalPriceEAT'    => $quantity * $product['priceEAT'] );
        } else {
            $this->products[$product['productId']]['quantity']         += $quantity;
            $this->products[$product['productId']]['totalVAT']         =
                $this->products[$product['productId']]['quantity'] *
                ($this->products[$product['productId']]['unitPriceIAT'] -
                $this->products[$product['productId']]['unitPriceEAT']);
            $this->products[$product['productId']]['totalPriceIAT']    =
                $this->products[$product['productId']]['quantity'] *
                $this->products[$product['productId']]['unitPriceIAT'];
            $this->products[$product['productId']]['totalPriceEAT']    =
                $this->products[$product['productId']]['quantity'] *
                $this->products[$product['productId']]['unitPriceEAT'];
        }

        $this->save();
        $this->getJSON();
    }

    public function removeProduct($productId, $quantity, $basketId = null)
    {
        $this->setBasketId($basketId);
        $this->load();

        if ($this->products[$productId]['quantity'] - $quantity <= 0) {
            unset($this->products[$productId]);
        } else {
            $this->products[$productId]['quantity'] = $this->products[$productId]['quantity'] - $quantity;
            $this->products[$productId]['totalPriceIAT'] =
                $this->products[$productId]['unitPriceIAT'] * $this->products[$productId]['quantity'];
            $this->products[$productId]['totalPriceEAT'] =
                $this->products[$productId]['unitPriceEAT'] * $this->products[$productId]['quantity'];
        }

        $this->save();
        $this->getJSON();
    }

    public function getJson($return = false)
    {
        $subtotalEAT = $this->getEATSubTotal();
        $subtotalIAT = $this->getIATSubTotal();
        $VATSubtotal = $this->getIATSubTotal() - $this->getEATSubTotal();

        $returnValue =  json_encode(array(
                    'basketId'    => $this->basketId,
                    'products'    => $this->products,
                    'subtotalEAT' => $subtotalEAT,
                    'VATSubtotal' => $VATSubtotal,
                    'subtotalIAT' => $subtotalIAT));

        if (true === $return) {
            return $returnValue;
        } else {
            echo $returnValue;
        }
    }

    public function getIATSubTotal()
    {
        $IATSubtotal = 0;

        if (true === is_array($this->products)) {
            reset($this->products);
            foreach ($this->products as $productArray) {
                $IATSubtotal += $productArray['totalPriceIAT'];
            }
        }

        return $IATSubtotal;
    }

    public function getEATSubTotal()
    {
        $EATSubtotal = 0;

        if (true === is_array($this->products)) {
            reset($this->products);
            foreach ($this->products as $productArray) {
                $EATSubtotal += $productArray['totalPriceEAT'];
            }
        }

        return $EATSubtotal;
    }
}
