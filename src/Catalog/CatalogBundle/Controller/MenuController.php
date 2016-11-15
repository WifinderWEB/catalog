<?php

namespace Catalog\CatalogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Session\Session;

class MenuController extends Controller {

    public function mainMenuAction($alias = 'main') {
        $items = array();
        if ($this->container->get('session')->get('project_id')) {
            $items[] = array('id' => 1, 'title' => 'Каталог', 'link' => 'Catalog_CatalogBundle_Catalog_list');
            $items[] = array('id' => 2, 'title' => 'Контент', 'link' => 'Catalog_ContentBundle_Content_list');

            $items[] = array('id' => 4, 'title' => 'Магазин', 'child' => array(
                   array('id' => 14, 'title' => 'Склады', 'link' => 'Catalog_StockBundle_Stock_list'),
                   array('id' => 15, 'title' => 'Заказы', 'link' => 'Catalog_OrderBundle_Order_list')
            ));
//            $items[] = array('id' => 3, 'title' => 'Теги', 'link' => 'Catalog_TagBundle_Tag_list');
            $items[] = array('id' => 16, 'title' => 'Теги', 'child' => array(
                array('id' => 17, 'title' => 'Теги', 'link' => 'Catalog_TagBundle_Tag_list'),
                array('id' => 18, 'title' => 'Категории', 'link' => 'Catalog_CategoryBundle_Category_list'),
                array('id' => 19, 'title' => 'Параметры', 'link' => 'Catalog_CategoryBundle_Group_list'),
            ));
            $items[] = array('id' => 4, 'title' => 'Галереи', 'child' => array(
                    array('id' => 5, 'title' => 'Фото галереи', 'link' => 'Catalog_ImageGalleryBundle_Image_category_list'),
                    array('id' => 6, 'title' => 'Видео галереи', 'link' => 'Catalog_VideoGalleryBundle_Video_category_list'),
            ));
            $items[] = array('id' => 10, 'title' => 'Файлы', 'child' => array(
                    array('id' => 11, 'title' => 'Категории', 'link' => 'Catalog_FileGalleryBundle_File_category_list'),
                    array('id' => 12, 'title' => 'Файлы', 'link' => 'Catalog_FileGalleryBundle_File_list'),
            ));
        }
        if (true === $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $items[] = array('id' => 7, 'title' => 'Настройки', 'child' => array(
                    array('id' => 8, 'title' => 'Пользователи', 'link' => 'Catalog_UserBundle_User_list'),
                    array('id' => 9, 'title' => 'Проекты', 'link' => 'Catalog_ProjectBundle_Project_list'),
                    array('divider' => true),
                    array('id' => 9, 'title' => 'Поля', 'link' => 'Catalog_ProjectBundle_Field_list'),
            ));
        }

        return $this->render(
                        'CatalogCatalogBundle:Menu:_main.html.twig', array('items' => $items)
        );
    }

}
