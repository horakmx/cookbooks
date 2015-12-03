<?php

use Phalcon\Mvc\Model;

#class Stores extends Model
class Stores 
{
    const wsGatewayHost  = 'http://common.gateway.wsint.dev.dsg-i.com/index.php/ServerRest/index/?xml=false&dir=webServices';
	const siteId 		= 383;
 
    public function makeRequest($model, $method, $params, $required = array(), $options = array())
	{
		// create a new cURL resource
		$curlResource = curl_init();

		$message = 'rm=' . $model . '&rf=' . $method;
		$requestUrl = self::wsGatewayHost;
	   	$message .= '&params=' . serialize($params) . '&required=' . serialize($required) . '&options=' . serialize($options) ;

		// set URL and other appropriate options
		curl_setopt($curlResource, CURLOPT_URL, $requestUrl);
		curl_setopt($curlResource, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlResource, CURLOPT_POST, true);
		curl_setopt($curlResource, CURLOPT_POSTFIELDS, http_build_query(array(serialize($message))));

		// grab URL and pass it to the browser
		$response =	curl_exec($curlResource);

		// close cURL resource, and free up system resources
		curl_close($curlResource);

		$array_response = unserialize($response);

		if ( true === is_array($array_response) && true === isset($array_response[0]['ROWS']))
		{
			return json_encode($array_response[0]['ROWS']);
		}
		else
		{
			return json_encode(array('error_code' => $array_response[0]['ROWS']['Web_Services_Errno'], 'error_message' => $array_response[0]['ROWS']['Web_Services_Error']));
		}
	}

	public function getById($storeId, $return = false)
	{
	   	$return = $this->makeRequest('stores', 'STORE_GetInformationsById', array('ISTOREID' => $storeId), array('SITE_ID' => self::siteId));

		if( $return === false)
		{
			echo $return;
		}
		else
		{
			return $return;
		}
	}

	public function getByNumber($storeNumber, $return = false)
	{
	   	$return = $this->makeRequest('stores', 'STORE_GetInformationsByNumber', array('SSTORENUMBER' => $storeNumber), array('ISITEID' => self::siteId));

		if( $return === false)
		{
			echo $return;
		}
		else
		{
			return $return;
		}
	}

	public function getCoordinates($location)
	{
		$curlResource = curl_init();
		curl_setopt($curlResource, CURLOPT_URL, 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($location));
		curl_setopt($curlResource, CURLOPT_RETURNTRANSFER, true);

		$response = json_decode(curl_exec($curlResource));

		curl_close($curlResource);

		$coordinates = $response->results[0]->geometry->location;

		return $coordinates->lat . ',' . $coordinates->lng;
	}

	public function getExternalRef($product)
	{
		$curlResource = curl_init();
		curl_setopt($curlResource, CURLOPT_URL, 'http://stage.currys.guided.lon5.atomz.com/?do=mobile&sp_staged=1&sp_cs=UTF-8i&q=' . urlencode($product));
		curl_setopt($curlResource, CURLOPT_RETURNTRANSFER, true);

		$response = json_decode(curl_exec($curlResource));

		curl_close($curlResource);

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
	   	$productsArray 		= explode(',', $products);
		$requestProducts	= array();

		$oProduct = new Products();

		foreach ($productsArray as $product)
		{
			$arrayProduct = explode('-', $product);
			$externalSku = $this->getExternalRef($arrayProduct[0]);

			$requestProducts[] = array
			   (
			   		'sFUPID' 		=> $arrayProduct[0],
					'sSKU'	 		=> $externalSku,
					'iQuantity'		=> $arrayProduct[1],
					'bIsBundle'		=> 0,
					'aItems'		=> null
			   );
		}

		$stores = $this->makeRequest('stores', 'STORE_GetNearestStoresAndAvailabilityByGridReference', 
			  	array( 'SGRIDREF' 		=> $coordinates,
				   	   'ISITEID'  		=> self::siteId,
					   'IMAXSTORES'     => 9,
					   'BUSEQAS'		=> false,
					   'SDISTANCEUNIT'	=> 'M',
					   'IOFFSET'		=> 40,
					   'APRODUCTS'		=> $requestProducts,
					   'BWITHNEAREST'	=> true
				   	  ));

			$aStores = json_decode($stores, true);

			foreach($aStores as $storeIndex => $aStore)
			{
			   	foreach( $aStore['aProducts'] as $product)
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
			    		$store = json_decode($this->getById($aStore['iStoreId']), true);
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
			   	 'SCOSTCENTRE' => $storeId,
				 'ACUSTOMERDETAILS' => array
				 (
				 	'Title' => $title,
					'Firstname' => $firstname,
					'Surname' => $surname,
					'ContactPhoneNumber' => $contactnumber,
					'FlightNumber' => ''
				 ),
				 'APRODUCTS'  => array(),
				 'SEXPIRYDATE' => date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))) . 'T22:59:00'
			  );

		$oBasket = new Basket();
		$oBasket->Load($basketId);

		$products = $oBasket->getProducts();

		foreach($products as $productId => $productArray)
		{
			$aParams['APRODUCTS']['ItemLine'][] = array
			(
				'AdjustedPrice' 		=> array('Amount' => $productArray['unitPriceIAT'], 'Currency' => 'GBP'),
				'ProductCode'			=> $this->getExternalRef($productId),
				'ProductDescription'	=> str_replace(' ','+', $productArray['title']),
				'ProductQuantity'		=> $productArray['quantity']
			);
		}

		$return = $this->makeRequest('stores', 'STORE_MakeReservation', $aParams);

		if (0 < preg_match('/\["[A-Za-z0-9]{7}"\]/', $return))
		{
		    $store1 = json_decode($this->getByNumber($storeId), true);
			$store2 = json_decode($this->getById($store1['iStoreId']), true);

			echo json_encode(array
			(
				'reservationId' 	=> str_replace(array('[', '"', ']'), array(''), $return),
				'reservedFor'		=> $title . ' ' . $firstname . ' ' . $surname,
				'reservedFrom'		=> date('d/m/Y'),
				'reservedUntil'		=> date('d/m/Y', mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")) ),
				'store'             => $store2
			));
		}
	}
}

?>
