<?php
namespace CrdApi\Models;

abstract class NemoWsModel
{
    private $client;
    protected $requestUrl;
    protected $request;

    abstract protected function buildRequest(array $params);
    abstract protected function parseResponse($response);

    private function getClient($timeout)
    {
        // create a new guzzle resource
        $this->client = new \Guzzle\Http\Client(array(
                'timeout'  => $timeout,
        ));
    }

    public function request($params)
    {
        $this->getClient('10.0');
        $this->buildRequest($params);
        $method   = static::REQUESTMETHOD;
        $request  = $this->client->$method($this->requestUrl, array(), array($this->request));
        $response = $request->send();
        return json_encode($this->parseResponse(unserialize($response->getBody(true))));
    }

    public function externalRequest($method, $requestUrl, $request = '')
    {
        $this->getClient('2.0');
        $request  = $this->client->$method($requestUrl);
        $response = $request->send();
        return json_decode($response->getBody(true));
    }

    public function output($response, $return = false)
    {
        if ($return === false) {
            echo $response;
            return true;
        } else {
            return $response;
        }
    }
}
