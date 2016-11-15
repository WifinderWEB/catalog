<?php

namespace Catalog\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Catalog\ContentBundle\lib\AccessTokenAuthentication;

class TranslateController extends Controller {
    public function getTokenAction(){
        //Soap WSDL Url
        $wsdlUrl = "http://api.microsofttranslator.com/V2/Soap.svc";
        //Client ID of the application.
        $clientID = "WFCatalog";
        //Client Secret key of the application.
        $clientSecret = "W8Iz72QzsUjQ5HYOUwwov8G+yyfDFCHrHd22X/9Zam8=";
        //OAuth Url.
        $authUrl = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";
        //Application Scope Url
        $scopeUrl = "http://api.microsofttranslator.com";
        //Application grant type
        $grantType = "client_credentials";

        //Create the Authentication object
        $authObj = new AccessTokenAuthentication();
        //Get the Access token
        $accessToken = $authObj->getTokens($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl);

        return $this->render(
             'CatalogContentBundle:Translate:_token.html.twig', array('token' => $accessToken)
        );
    }
}

