<?php

namespace ContactBundle\Entity\Repository;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface ContactRepositoryInterface
{
    public function countNewContactRequests(): int;
}
