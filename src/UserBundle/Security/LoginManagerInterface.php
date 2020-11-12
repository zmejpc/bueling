<?php

namespace UserBundle\Security;

use UserBundle\Entity\UserInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface LoginManagerInterface
{
    /**
     * @param string        $firewallName
     * @param UserInterface $user
     * @param Response|null $response
     */
    public function logInUser(string $firewallName, UserInterface $user, Response $response = null);
}
