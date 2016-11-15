<?php

namespace Catalog\StockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CatalogStockBundle:Default:index.html.twig', array('name' => $name));
    }
}
