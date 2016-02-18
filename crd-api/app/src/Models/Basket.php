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

   public function load($basketId = 0)
   {
        if ($basketId !== 0)
        {
           $this->basketId = $basketId;
        }

        $this->getCache();

        if (false === empty($this->basketId))
        {
            $this->products = $this->cache->get($this->basketId);
        }
   }

   public function delete($basketId)
   {
       if ($basketId !== 0)
       {
           $this->basketId = $basketId;
       }

       $this->getCache();
       $this->cache->delete($this->basketId);
       $this->getJSON($this->basketId);
   }

   public function getProducts()
   {
       return $this->products;
   }

   private function setBasketId($basketId = null)
   {
      if (true === empty($basketId))
      {
          $basketId = uniqid(true);
      }

      $this->basketId = $basketId; 
   }

   public function addProduct($product, $quantity, $basketId = null)
   {
        $this->setBasketId($basketId);

        if (false === empty($basketId))
        {
            $this->load();
        }

        $oProduct       = new Products();
        $arrayProduct   = $oProduct->getById($product, true);

        if (false === isset($this->products[$product]))
        {
            $this->products[$product]     = array(
                                                    'productId'        => $product,
                                                    'title'            => $arrayProduct['title'],
                                                    'image'            => $arrayProduct['image'], 
                                                    'quantity'         => (int) $quantity, 
                                                    'unitPriceIAT'     => $arrayProduct['priceIAT'],
                                                    'unitPriceEAT'     => $arrayProduct['priceEAT'],
                                                    'totalVAT'         => $quantity * $arrayProduct['VAT'],
                                                    'totalPriceIAT'    => $quantity * $arrayProduct['priceIAT'],
                                                    'totalPriceEAT'    => $quantity * $arrayProduct['priceEAT'] );
        }
        else
        {
            $this->products[$product]['quantity']         += $quantity;
            $this->products[$product]['totalVAT']         = $this->products[$product]['quantity'] * ($this->products[$product]['unitPriceIAT'] - $this->products[$product]['unitPriceEAT']);
            $this->products[$product]['totalPriceIAT']    = $this->products[$product]['quantity'] * $this->products[$product]['unitPriceIAT'];
            $this->products[$product]['totalPriceEAT']    = $this->products[$product]['quantity'] * $this->products[$product]['unitPriceEAT'];
        }

        $this->save();
        $this->getJSON($this->basketId);
   }

   public function removeProduct($product, $quantity, $basketId)
   {
        $this->setBasketId($basketId);
        $this->load();

        if ($this->products[$product]['quantity'] - $quantity <= 0)
        {
            unset($this->products[$product]);
        }
        else
        {
            $this->products[$product]['quantity'] = $this->products[$product]['quantity'] - $quantity;
            $this->products[$product]['totalPriceIAT'] =  $this->products[$product]['unitPriceIAT'] * $this->products[$product]['quantity'];
            $this->products[$product]['totalPriceEAT'] =  $this->products[$product]['unitPriceEAT'] * $this->products[$product]['quantity'];
        }

        $this->save();
        $this->getJSON($this->basketId);
   }

   public function getJson($basketId)
   {
       if (false === is_null($basketId))
       {
           $this->setBasketId($basketId);
           $this->load();
       }
       $subtotalEAT = $this->getEATSubTotal();
       $subtotalIAT = $this->getIATSubTotal();
       $VATSubtotal = $this->getIATSubTotal() - $this->getEATSubTotal();

       echo json_encode(array('basketId' => $this->basketId, 'products' => $this->products, 'subtotalEAT' => $subtotalEAT, 'VATSubtotal' => $VATSubtotal, 'subtotalIAT' => $subtotalIAT));
   }

   private function getIATSubTotal()
   {
        $IATSubtotal = 0;

        if (true === is_array($this->products))
        {
            reset($this->products);
            foreach ($this->products as $productId => $productArray)
            {
                $IATSubtotal += $productArray['totalPriceIAT'];
            }
        }

        return $IATSubtotal;
   }

   private function getEATSubTotal()
   {
        $EATSubtotal = 0;

        if (true === is_array($this->products))
        {
            reset($this->products);
            foreach ($this->products as $productId => $productArray)
            {
                $EATSubtotal += $productArray['totalPriceEAT'];
            }
        }

        return $EATSubtotal;
   }
}
