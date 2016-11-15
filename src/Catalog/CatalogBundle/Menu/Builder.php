<?php

// src/Catalog/CatalogBundle/Menu/Builder.php

namespace Catalog\CatalogBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Session\Session;

class Builder extends ContainerAware {

    public function navbarMenu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('root');

        $menu->setChildrenAttributes(array('id' => 'main_navigation', 'class' => 'nav'));

        if ($this->container->get('session')->get('project_id')) {
            $menu->addChild('Catalog', array('route' => 'Catalog_CatalogBundle_Catalog_list'));
            $menu->addChild('Content', array('route' => 'Catalog_ContentBundle_Content_list'));
            $menu->addChild('Tags', array('route' => 'Catalog_TagBundle_Tag_list'));
            $menu->addChild('Image Gallery', array('route' => 'Catalog_ImageGalleryBundle_Image_category_list'));
            $menu->addChild('Video Gallery', array('route' => 'Catalog_VideoGalleryBundle_Video_category_list'));
        }
        if (true === $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $menu->addChild('Settings')
                    ->setAttribute('dropdown', true);
            $menu['Settings']->addChild('Users', array('route' => 'Catalog_UserBundle_User_list'));
            $menu['Settings']->addChild('Projects', array('route' => 'Catalog_ProjectBundle_Project_list'));
        }
        return $menu;
    }

}
