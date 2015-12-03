<?php

use Phalcon\Mvc\Model;

#class Products extends Model
class Products 
{
    const wsProductHost  = 'http://common.wsint-brain.wsint.dev.dsg-i.com/index.php?xml=false&d=webServices_Server&c=ServerRest&scallerapp=frontapi'; 
	const siteId 		 = 383;
	const countryCode 	 = 'GB';
	const languageCode 	 = 'UK';
 
    public function makeRequest($model, $method, $params)
	{
		// create a new cURL resource
		$curlResource = curl_init();

		$requestUrl = self::wsProductHost . '&rm=' . $model . '&rf=' . $method;

		foreach($params as $key => $value)
		{
			$requestUrl .= '&' . $key . '=' . urlencode($value);
		}

		// set URL and other appropriate options
		curl_setopt($curlResource, CURLOPT_URL, $requestUrl);
		curl_setopt($curlResource, CURLOPT_RETURNTRANSFER, true);

		// grab URL and pass it to the browser
		$response =	curl_exec($curlResource);

		// close cURL resource, and free up system resources
		curl_close($curlResource);

		$array_response = unserialize($response);

		if ( true === is_array($array_response) && true === isset($array_response[0]['ROWS'][0]))
		{
			return json_encode($array_response[0]['ROWS'][0]);
		}
		else
			return json_encode(array('error_code' => $array_response[0]['ROWS']['Web_Services_Errno'], 'error_message' => $array_response[0]['ROWS']['Web_Services_Error']));
	}

	public function getById($productId, $bReturn=false)
	{
		$productInformation = json_decode($this->makeRequest('productinfo', 'PRODUCTINFO_CollectProductInformations', array('SFUPID' => $productId, 'SDESCRIPTIONTYPE' => 'B2C', 'BWITHPRICENOTINBUNDLE' => 0, 'SCOUNTRY' => self::countryCode, 'SLANGUAGE' => self::languageCode, 'ISITEID' => self::siteId)));

		$return = array
		   	  (
			   	'productId' 	=> $productId,
			   	'title'  		=> $productInformation->PRODUCT_GetLabelsByLanguage[0]->sLabel,
				'priceEAT'		=> $productInformation->PRODUCT_GetPricesByCountry[0]->fPriceEAT,
				'priceIAT'		=> round($productInformation->PRODUCT_GetPricesByCountry[0]->fPriceEAT * (1 +  $productInformation->PRODUCT_GetPricesByCountry[0]->fTauxTVA / 100),2) ,
				'VAT'			=> ($productInformation->PRODUCT_GetPricesByCountry[0]->fPriceEAT * (1 +  $productInformation->PRODUCT_GetPricesByCountry[0]->fTauxTVA / 100) )- $productInformation->PRODUCT_GetPricesByCountry[0]->fPriceEAT,
				'stock'			=> $productInformation->PRODUCT_GetCoreInformationByCountry->{0}->iStockAvailableQty,
				'image'			=> 'http://brain-images.cdn.dixons.com/' . substr($productId, -1, 1) . '/' . substr($productId, -2, 1) . '/' . $productId . '/t_' . $productInformation->PRODUCT_GetPhotos[0]->sName,
				'isP&C'			=> $productInformation->PRODUCT_GetCoreInformationByCountry->{0}->aDeliveryInformations->{20}->ENABLED,
				'isHD'			=> $productInformation->PRODUCT_GetCoreInformationByCountry->{0}->aDeliveryInformations->{1}->ENABLED,
				'productType'	=> ($productInformation->PRODUCT_GetCoreInformationByCountry->{0}->aProductSourceInformations->{6}->ENABLED === 'Y' ? 'SMALLBOX' : 'BIGBOX' )
			  
			  );
  
		if (false === $bReturn)
			echo json_encode($return);
		else
			return $return;
	}

	public function getPrices($productId)
	{
		echo $this->makeRequest('products', 'PRODUCT_GetPricesByCountry', array('SFUPID' => $productId, 'SCOUNTRY' => self::countryCode, 'ISITEID' => self::siteId));
	}


}

?>
