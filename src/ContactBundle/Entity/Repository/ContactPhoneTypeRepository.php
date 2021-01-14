<?php

namespace ContactBundle\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use ContactBundle\Entity\ContactPhoneTypeInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ContactPhoneTypeRepository extends DashboardRepository
{
    public function getContactPhoneType()
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, t, contactPhones')
            ->leftJoin('q.translations', 't')
            ->leftJoin('q.contactPhones', 'contactPhones', 'WITH', 'contactPhones.showOnWebsite = :showOnWebsite')
            ->andWhere('q.showOnWebsite =:showOnWebsite')
            ->setParameter('showOnWebsite', ContactPhoneTypeInterface::YES);

        return $query->getQuery()->getResult();
    }

    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        $query = $this->createQueryBuilder('q')
            ->where('q.id =:id')
            ->setParameter('id', $id);

        return $query->getQuery()->getOneOrNullResult();
    }
}