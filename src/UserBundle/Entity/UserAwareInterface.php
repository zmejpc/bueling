<?php

namespace UserBundle\Entity;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface UserAwareInterface
{
    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface;

    /**
     * @param UserInterface|null $user
     */
    public function setUser(UserInterface $user);
}
