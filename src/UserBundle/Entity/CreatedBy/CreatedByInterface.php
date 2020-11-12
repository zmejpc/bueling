<?php

namespace UserBundle\Entity\CreatedBy;

use UserBundle\Entity\UserInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface CreatedByInterface
{
    /**
     * @param UserInterface $createdBy
     */
    public function setCreatedBy(UserInterface $createdBy): void;

    /**
     * @return UserInterface|null
     */
    public function getCreatedBy(): ?UserInterface;
}
