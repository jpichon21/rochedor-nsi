<?php

namespace AppBundle\Security;

use AppBundle\Entity\Contact;
use AppBundle\Exception\AccountDeletedException;
use AppBundle\Security\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        return;
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof Contact) {
            return;
        }

        // user account is expired, the user may be notified
        if (!empty($user->getCatco2())) {
            throw new BadCredentialsException();
        }
    }
}
