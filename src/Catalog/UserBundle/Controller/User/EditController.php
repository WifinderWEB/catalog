<?php

namespace Catalog\UserBundle\Controller\User;

use Admingenerated\CatalogUserBundle\BaseUserController\EditController as BaseEditController;

/**
 * EditController
 */
class EditController extends BaseEditController {

    protected function saveObject(\Catalog\UserBundle\Entity\User $User) {
        $userManager = $this->get('fos_user.user_manager');
        $userManager->updateUser($User);
    }

}
