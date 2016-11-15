<?php

namespace Catalog\CatalogBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CatalogListener {

    private $router;
    private $container;

    public function __construct($router, $container) {
        $this->router = $router;
        $this->container = $container;
    }

    public function onKernelController(FilterControllerEvent $event) {
        $currentController = $event->getController();

        $url = $this->router->generate('homepage');
        $route = $event->getRequest()->get('_route');

        if (!$this->container->get('session')->get('project_id')){
            if(strpos($route, 'Catalog_CatalogBundle_Catalog') !== false ||
                    strpos($route, 'Catalog_ContentBundle_Content') !== false ||
                    strpos($route, 'Catalog_ImageGalleryBundle_Image') !== false ||
                    strpos($route, 'Catalog_VideoGalleryBundle_Video') !== false ||
                    strpos($route, 'Catalog_CategoryBundle_Category') !== false ||
                    strpos($route, 'Catalog_TagBundle_Tag') !== false){
                $event->setController(function() use ($url) {
                    return new RedirectResponse($url);
                });
            }
        }
    }

}
