<?php

namespace Catalog\UserBundle\Controller\User;

use Admingenerated\CatalogUserBundle\BaseUserController\NewController as BaseNewController;

/**
 * NewController
 */
class NewController extends BaseNewController {

    protected function getNewObject()
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setEnabled(true);
        return $user;
    }

    
    protected function saveObject(\Catalog\UserBundle\Entity\User $user) {
        $userManager = $this->get('fos_user.user_manager');
        $user->addRole('ROLE_ADMIN');
        $user->setSuperAdmin(false);
        $userManager->updateUser($user);
    }

}
