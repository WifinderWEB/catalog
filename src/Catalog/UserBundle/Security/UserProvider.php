<?php

namespace Catalog\UserBundle\Security;

use FOS\UserBundle\Security\UserProvider as FOSProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class UserProvider extends FOSProvider {


    public function __construct(UserManagerInterface $userManager) {
        parent::__construct($userManager);
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $user = $this->findUser($username);
//        foreach($user->getProjects() as $one){
//            
//        }
        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return $user;
    }
}