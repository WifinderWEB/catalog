<?php

namespace Catalog\ContentBundle\Controller;

use Catalog\ContentBundle\Entity\Category;
use Catalog\ContentBundle\Form\ContentParametersType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Catalog\ContentBundle\lib\AccessTokenAuthentication;

class AjaxController extends Controller {

    public function indexAction($text) {
        try {
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
            //Create soap translator Object
            var_dump($accessToken); exit;
            $soapTranslator = $this->getSoap($accessToken, $wsdlUrl);
            
            //Set the params.//
            $fromLanguage = "ru";
            $toLanguage = "en";

            //Request argument list.
            $requestArg = array(
                'text' => $text,
                'from' => $fromLanguage,
                'to' => $toLanguage,
                'contentType' => 'text/plain',
                'category' => 'general'
            );

           
            //$responseObj = $soapTranslator->objSoap->Translate($requestArg);
            var_dump($soapTranslator);

        } catch (Exception $e) {
            var_dump( "Exception: " . $e->getMessage() ); exit;
        }
    }
    
    private function getSoap($accessToken, $wsdlUrl){
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
            var_dump($wsdlUrl); exit;
            return new \SoapClient($wsdlUrl, $optionsArr);
        } catch(Exception $e){
            var_dump($e); exit;
        }
    }

    public function getGroupParametersAction(){
        $id = $this->get('request')->query->get('id');
        $category = $this->getDoctrine()->getRepository('CatalogCategoryBundle:Category')->find($id);
        if($category){
            $pattern = new Category();
            $pattern->setCategory($category);

            $type = new ContentParametersType($pattern);
            $form = $this->createForm($type, null, array('csrf_protection' => false));

            return $this->render('CatalogContentBundle:ContentEdit:_parameters_form.html.twig', array(
                'form' => $form->createView(),
            ));
        }

        return $this->render('CatalogContentBundle:ContentEdit:_parameters_form.html.twig', array(
        ));
    }
}
