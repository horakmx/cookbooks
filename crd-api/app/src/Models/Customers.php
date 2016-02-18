<?php

use Phalcon\Mvc\Model;
namespace CrdApi\Models;

class Customers extends NemoWsModel
{
    const host          = 'http://common.ws-client.wsint.dev.dsg-i.com/?d=webServices/Server&c=ServerRest';
	const siteId 	    = 387;
    const requestMethod = 'get';

    protected function buildRequest(array $params)
    {
		$this->requestUrl = self::host . '&rm=' . $params['model'] . '&rf=' . $params['method'];

		foreach($params['params'] as $key => $value)
		{
			$this->requestUrl .= '&' . $key . '=' . urlencode($value);
		}
    }

    protected function parseResponse($response)
    {
		if ( true === is_array($response) && $response[0]['bStatus'] === 1)
		{
			return $response[0]['mResult'][0];
		}
		else
			return array('error_code' => $response[0]['iError'], 'error_message' => $response[0]['mResult']);
    }
	
    public function getById($customerId)
	{
		echo $this->request(array('model' => 'wsContact', 'method' => 'getContact', 'params' => array('CONTACT_ID' => $customerId, 'SITE_ID' => self::siteId)));
	}

    public function authenticate($login, $password)
	{
		echo $this->request(array('model' => 'wsContact', 'method' => 'getContact', 'params' => array('LOGIN' => $login, 'PASSWORD' => $password, 'SITE_ID' => self::siteId)));
	}


}

?>
