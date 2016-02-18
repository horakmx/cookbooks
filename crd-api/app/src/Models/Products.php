<?php

use Phalcon\Mvc\Model;
namespace CrdApi\Models;

class Products extends NemoWsModel
{
    const host           = 'http://common.wsint-brain.wsint.dev.dsg-i.com/index.php?xml=false&d=webServices_Server&c=ServerRest&scallerapp=frontapi'; 
    const requestMethod  = 'get';
    const siteId         = 383;
    const countryCode    = 'GB';
    const languageCode   = 'UK';

    protected function buildRequest(array $params)
    {
        $this->requestUrl = self::host . '&rm=' . $params['model'] . '&rf=' . $params['method'];
        foreach($params['params'] as $key => $value)
        {
            $this->requestUrl .= '&' . $key . '=' . urlencode($value);
        }
    }

    protected function  parseResponse($response)
    {
        if ( true === is_array($response) && true === isset($response[0]['ROWS'][0]))
        {
            return $response[0]['ROWS'][0];
        }
        else
        {
            return array('error_code' => $response[0]['ROWS']['Web_Services_Errno'], 'error_message' => $response[0]['ROWS']['Web_Services_Error']);
        }
    }

    public function getById($productId, $bReturn=false)
    {
        $productInformation = json_decode($this->request(array('model'=> 'productinfo', 'method' => 'PRODUCTINFO_CollectProductInformations', 'params' => array('SFUPID' => $productId, 'SDESCRIPTIONTYPE' => 'B2C', 'BWITHPRICENOTINBUNDLE' => 0, 'SCOUNTRY' => self::countryCode, 'SLANGUAGE' => self::languageCode, 'ISITEID' => self::siteId))));

        $return = array
                 (
                  'productId'     => $productId,
                  'title'         => $productInformation->PRODUCT_GetLabelsByLanguage[0]->sLabel,
                  'priceEAT'      => $productInformation->PRODUCT_GetPricesByCountry[0]->fPriceEAT,
                  'priceIAT'      => round($productInformation->PRODUCT_GetPricesByCountry[0]->fPriceEAT * (1 +  $productInformation->PRODUCT_GetPricesByCountry[0]->fTauxTVA / 100),2) ,
                  'VAT'           => ($productInformation->PRODUCT_GetPricesByCountry[0]->fPriceEAT * (1 +  $productInformation->PRODUCT_GetPricesByCountry[0]->fTauxTVA / 100) )- $productInformation->PRODUCT_GetPricesByCountry[0]->fPriceEAT,
                  'stock'         => $productInformation->PRODUCT_GetCoreInformationByCountry->{0}->iStockAvailableQty,
                  'image'         => 'http://brain-images.cdn.dixons.com/' . substr($productId, -1, 1) . '/' . substr($productId, -2, 1) . '/' . $productId . '/t_' . $productInformation->PRODUCT_GetPhotos[0]->sName,
                  'isP&C'         => $productInformation->PRODUCT_GetCoreInformationByCountry->{0}->aDeliveryInformations->{20}->ENABLED,
                  'isHD'          => $productInformation->PRODUCT_GetCoreInformationByCountry->{0}->aDeliveryInformations->{1}->ENABLED,
                  'productType'   => ($productInformation->PRODUCT_GetCoreInformationByCountry->{0}->aProductSourceInformations->{6}->ENABLED === 'Y' ? 'SMALLBOX' : 'BIGBOX' )
              );

        if (false === $bReturn)
        {
            echo json_encode($return);
        }
        else
        {
            return $return;
        }
    }

    public function getPrices($productId)
    {
        echo $this->request(array('model' => 'products', 'method' => 'PRODUCT_GetPricesByCountry', 'params' => array('SFUPID' => $productId, 'SCOUNTRY' => self::countryCode, 'ISITEID' => self::siteId)));
    }
}
?>
