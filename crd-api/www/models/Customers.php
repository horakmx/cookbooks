<?php

use Phalcon\Mvc\Model;

#class Customers extends Model
class Customers 
{
    const wsClientHost  = 'http://common.ws-client.wsint.dev.dsg-i.com/?d=webServices/Server&c=ServerRest';
	const siteId 		= 387;
 
    public function makeRequest($model, $method, $params)
	{
		// create a new cURL resource
		$curlResource = curl_init();

		$requestUrl = self::wsClientHost . '&rm=' . $model . '&rf=' . $method;

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

		if ( true === is_array($array_response) && $array_response[0]['bStatus'] === 1)
		{
			return json_encode($array_response[0]['mResult'][0]);
		}
		else
			return json_encode(array('error_code' => $array_response[0]['iError'], 'error_message' => $array_response[0]['mResult']));
	}

	public function getById($customerId)
	{
		echo $this->makeRequest('wsContact', 'getContact', array('CONTACT_ID' => $customerId, 'SITE_ID' => self::siteId));
	}

	public function authenticate($login, $password)
	{
		echo $this->makeRequest('wsContact', 'getContact', array('LOGIN' => $login, 'PASSWORD' => $password, 'SITE_ID' => self::siteId));
	}


}

?>
