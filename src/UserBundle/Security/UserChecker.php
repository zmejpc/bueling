<?php

namespace UserBundle\Security;

use UserBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class UserChecker implements UserCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user instanceof User) {
            @trigger_error(sprintf('Calling %s with an AdvancedUserInterface is deprecated since Symfony 4.1. Create a custom user checker if you wish to keep this functionality.', __METHOD__), E_USER_DEPRECATED);
        }

        if (!$user->isAccountNonLocked()) {
            $ex = new LockedException('User account is locked.');
            $ex->setUser($user);
            throw $ex;
        }

        if (!$user->isEnabled()) {
            $ex = new DisabledException('User account is disabled.');
            $ex->setUser($user);
            throw $ex;
        }

        if (!$user->isAccountNonExpired()) {
            $ex = new AccountExpiredException('User account has expired.');
            $ex->setUser($user);
            throw $ex;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user instanceof User) {
            @trigger_error(sprintf('Calling %s with an AdvancedUserInterface is deprecated since Symfony 4.1. Create a custom user checker if you wish to keep this functionality.', __METHOD__), E_USER_DEPRECATED);
        }

        if (!$user->isCredentialsNonExpired()) {
            $ex = new CredentialsExpiredException('User credentials have expired.');
            $ex->setUser($user);
            throw $ex;
        }
    }
}
