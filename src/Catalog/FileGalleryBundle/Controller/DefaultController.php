<?php

namespace Catalog\FileGalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CatalogFileGalleryBundle:Default:index.html.twig', array('name' => $name));
    }
}
