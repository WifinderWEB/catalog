<?php

namespace Catalog\ContentBundle\lib;

use Catalog\ContentBundle\lib\AccessTokenAuthentication;

class SOAPMicrosoftTranslator {

    function getSoap($accessToken, $wsdlUrl){
        try {
            //Authorization header string.
            $authHeader = "Authorization: Bearer ". $accessToken;
            $contextArr = array(
                'http'   => array(
                    'header' => $authHeader
            )
            );
            //Create a streams context.
            $objContext = stream_context_create($contextArr);
            $optionsArr = array (
                'soap_version'   => 'SOAP_1_2',
                'encoding'          => 'UTF-8',
                'exceptions'      => true,
                'trace'          => true,
                'cache_wsdl'     => 'WSDL_CACHE_NONE',
                'stream_context' => $objContext,
                'user_agent'     => 'PHP-SOAP/'.PHP_VERSION."\r\n".$authHeader    
            );
            //Call Soap Client.
            return new \SoapClient($wsdlUrl, $optionsArr);
        } catch(Exception $e){
            var_dump($e); exit;
        }
    }
}

