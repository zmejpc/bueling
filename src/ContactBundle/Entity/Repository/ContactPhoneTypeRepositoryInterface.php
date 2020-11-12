<?php

namespace ContactBundle\Entity\Repository;

use Doctrine\Common\Collections\Collection;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface ContactPhoneTypeRepositoryInterface
{
    public function getContactPhoneType(): Collection;
}