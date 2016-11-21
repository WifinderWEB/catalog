<?php

namespace Api\CatalogBundle\Controller;

use Catalog\ContentBundle\Entity\Content;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Catalog\CatalogBundle\Entity\Repository\CatalogRepository;

class ProjectController extends Controller
{
    public function getProjectListAction()
    {
        $result = array();

        $projects = $this->getDoctrine()->getRepository('CatalogProjectBundle:Project')->findBy(array('is_active' => true));

        if(!$projects)
            $result = $this->error404();
        else {
            $background = array(
                '#004d8f', '#0099da', '#ffcc33', '#1d619c', '#ed1b2e', '#0659a4', '#ff6600', '#12326f', '#019ec9', '#404040', '#046bae', '#00acf0',
                '#004d8f', '#0099da', '#ffcc33', '#1d619c', '#ed1b2e', '#0659a4', '#ff6600', '#12326f', '#019ec9', '#404040', '#046bae', '#00acf0',
                '#004d8f', '#0099da', '#ffcc33', '#1d619c', '#ed1b2e', '#0659a4', '#ff6600', '#12326f', '#019ec9', '#404040', '#046bae', '#00acf0',
                '#004d8f', '#0099da', '#ffcc33', '#1d619c', '#ed1b2e', '#0659a4', '#ff6600', '#12326f', '#019ec9', '#404040', '#046bae', '#00acf0'
            );
            foreach($projects as $i => $one){
    //            if($projects->getJoinCatalog()->count() > 0){
                $result[] = array(
                    'id' => $one->getId(),
                    'title' => $one->getTitle(),
                    'alias' => $one->getAlias(),
                    'image_path' => $this->get('request')->getHost() . '' . $one->getWebPath(),
                    'background' => $background[$i]
                );
    //            }
            }

            $result = array('result' => array('items' => $result));
        }

        return new JsonResponse($result);
    }
}