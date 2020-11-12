<?php

namespace UserBundle\Entity\UpdatedBy;

use UserBundle\Entity\UserInterface;
use Doctrine\Common\Collections\Collection;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface UpdatedByInterface
{
    /**
     * @return Collection|UserInterface[]
     */
    public function getUpdatedBy(): Collection;

    /**
     * @param UserInterface $updatedBy
     */
    public function addUpdatedBy(UserInterface $updatedBy): void;

    /**
     * @param UserInterface $updatedBy
     */
    public function removeUpdatedBy(UserInterface $updatedBy): void;
}