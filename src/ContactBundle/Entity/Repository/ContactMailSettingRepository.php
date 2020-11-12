<?php

namespace ContactBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ContactMailSettingRepository extends EntityRepository
{
    public function getElementForEditForm()
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, t')
            ->leftJoin('q.translations', 't')
            ->setMaxResults(1);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getElement()
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, t')
            ->leftJoin('q.translations', 't')
            ->setMaxResults(1);

        return $query->getQuery()->getOneOrNullResult();
    }
}