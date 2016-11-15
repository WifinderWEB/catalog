<?php

namespace Catalog\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProjectController extends Controller
{
    public function indexAction()
    {
       return  $this->redirect($this->generateUrl('homepage'));
    }
    
    public function dashboardAction(){
        
        $projects = $this->getUser()->getProjects();
        
        return $this->render('CatalogProjectBundle:Project:dashboard.html.twig',
                array(
                    'projects' => $projects
                ));
    }
    
    public function selectProjectAction($id){
        $projects = $this->getUser()->getProjects();
        $test = false;
        foreach($projects as $one){
            if($one->getId() == $id){
                $test = true;
                break;
            }
        }
        
        if(!$test){
             $this->get('session')->getFlashBag()->add('error',  $this->get('translator')->trans("Select the project", array(), 'Admingenerator') );
             return  $this->redirect($this->generateUrl('homepage'));
        }
        else{
            $this->get('session')->set('project_id', $id);
            return  $this->redirect($this->generateUrl('Catalog_CatalogBundle_Catalog_list'));
        }
    }
}

