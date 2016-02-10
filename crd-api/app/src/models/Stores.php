<?php

namespace CrdApi\Models;

class Stores extends NemoWsModel
{
    const host           = 'http://common.gateway.wsint.dev.dsg-i.com/index.php/ServerRest/index/?xml=false&dir=webServices';
    const siteId         = 383;
    const requestMethod  = 'post';

    protected function buildRequest(array $params)
    {
        $this->requestUrl = self::host;
        $this->request = serialize('rm=' . $params['model'] . '&rf=' . $params['method'] . '&params=' . serialize($params['params']) . '&required='
                         . serialize(isset($params['required']) ? $params['required']: array()) . '&options=' . serialize(isset($params['options']) ? $params['options'] : array()));
    }

    protected function parseResponse($response)
    {
        if ( true === is_array($response) && true === isset($response[0]['ROWS']))
        {
            return $response[0]['ROWS'];
        }
        else
        {
            return array('error_code' => $response[0]['ROWS']['Web_Services_Errno'], 'error_message' => $response[0]['ROWS']['Web_Services_Error']);
        }
    }

    public function getById($storeId, $return = false)
    {
        $response = $this->request(array('model' => 'stores', 'method' => 'STORE_GetInformationsById', 'params' => array('ISTOREID' => $storeId), 'required' => array('SITE_ID' => self::siteId)));
        return $this->output($response, $return);
    }

    public function getByNumber($storeNumber, $return = false)
    {
        $response = $this->request(array('model' => 'stores', 'method' => 'STORE_GetInformationsByNumber', 'params' => array('SSTORENUMBER' => $storeNumber), 'required' => array('ISITEID' => self::siteId)));
        return $this->output($response, $return);
    }

    public function getCoordinates($location)
    {
        $response         = $this->externalRequest('get', 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($location));
        $coordinates    = $response->results[0]->geometry->location;

        return $coordinates->lat . ',' . $coordinates->lng;
    }

    public function getExternalRef($product)
    {
        $response    = $this->externalRequest('get', 'http://stage.currys.guided.lon5.atomz.com/?do=mobile&sp_staged=1&sp_cs=UTF-8i&q=' . urlencode($product));
        $externalRef = $response->resultsets->default->results[0]->externalref;

        return $externalRef;
    }

    public function getNearestStoresByLocation($location, $products)
    {
        $coordinates = $this->getCoordinates($location);
        echo $this->getNearestStores($coordinates, $products);
    }

    public function getNearestStoresByCoordinates($coordinates, $products)
    {
        echo $this->getNearestStores($coordinates, $products);
    }

    public function getNearestStores($coordinates, $products)
    {
        $productsArray      = explode(',', $products);
        $requestProducts    = array();

        $oProduct = new Products();

        foreach ($productsArray as $product)
        {
            $arrayProduct = explode('-', $product);
            $externalSku  = $this->getExternalRef($arrayProduct[0]);

            $requestProducts[] = array
               (
                    'sFUPID'         => $arrayProduct[0],
                    'sSKU'           => $externalSku,
                    'iQuantity'      => $arrayProduct[1],
                    'bIsBundle'      => 0,
                    'aItems'         => null
               );
        }

        $stores = $this->request(array('model' => 'stores', 'method' => 'STORE_GetNearestStoresAndAvailabilityByGridReference',
                  'params' => array( 'SGRIDREF'         => $coordinates,
                                      'ISITEID'          => self::siteId,
                                      'IMAXSTORES'       => 9,
                                      'BUSEQAS'          => false,
                                      'SDISTANCEUNIT'    => 'M',
                                      'IOFFSET'          => 40,
                                      'APRODUCTS'        => $requestProducts,
                                      'BWITHNEAREST'     => true
                                   )));

            $aStores = json_decode($stores, true);

            foreach($aStores as $storeIndex => $aStore)
            {
                foreach($aStore['aProducts'] as $product)
                {
                    $bShow = true;
                    if ($product['bIsAvailable'] === false)
                    {
                        $bShow = false;
                    }

                    if ( $bShow === false)
                    {
                        unset($aStores[$storeIndex]);
                    }
                    else
                    {
                        $store = json_decode($this->getById($aStore['iStoreId'], true), true);
                        $aStores[$storeIndex]['aOpeningHours'] = $store['aOpeningHours'];
                    }
                }
            }

            return json_encode($aStores);
    }

    public function makeReservation($basketId, $storeId, $title, $firstname, $surname, $contactnumber)
    {
        $aParams = array
                (
                 'SCOSTCENTRE'      => $storeId,
                 'ACUSTOMERDETAILS' => array
                                            (
                                             'Title'              => $title,
                                             'Firstname'          => $firstname,
                                             'Surname'            => $surname,
                                             'ContactPhoneNumber' => $contactnumber,
                                             'FlightNumber'       => ''
                                            ),
                 'APRODUCTS'       => array(),
                 'SEXPIRYDATE'     => date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))) . 'T22:59:00'
                );

        $oBasket = new Basket();
        $oBasket->Load($basketId);

        $products = $oBasket->getProducts();

        foreach($products as $productId => $productArray)
        {
            $aParams['APRODUCTS']['ItemLine'][] = array
            (
                'AdjustedPrice'         => array('Amount' => $productArray['unitPriceIAT'], 'Currency' => 'GBP'),
                'ProductCode'           => $this->getExternalRef($productId),
                'ProductDescription'    => str_replace(' ','+', $productArray['title']),
                'ProductQuantity'       => $productArray['quantity']
            );
        }

        $return = $this->request(array('model' => 'stores', 'method' => 'STORE_MakeReservation', 'params' => $aParams));

        if (0 < preg_match('/\["[A-Za-z0-9]{7}"\]/', $return))
        {
            $store1 = json_decode($this->getByNumber($storeId, true), true);
            $store2 = json_decode($this->getById($store1['iStoreId'], true), true);

            $this->output(json_encode(array
            (
                'reservationId'     => str_replace(array('[', '"', ']'), array(''), $return),
                'reservedFor'       => $title . ' ' . $firstname . ' ' . $surname,
                'reservedFrom'      => date('d/m/Y'),
                'reservedUntil'     => date('d/m/Y', mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")) ),
                'store'             => $store2
            )));
        }
    }
}
?>
