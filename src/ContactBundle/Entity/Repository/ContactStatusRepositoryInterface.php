<?php

namespace ContactBundle\Entity\Repository;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface ContactStatusRepositoryInterface
{
    public function getContactStatusesForContactForm(): QueryBuilder;
}
