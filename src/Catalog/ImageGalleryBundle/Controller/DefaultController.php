<?php

namespace Catalog\ImageGalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CatalogImageGalleryBundle:Default:index.html.twig', array('name' => $name));
    }
}
