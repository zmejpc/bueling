<?php

namespace UserBundle\Entity\Repository;

use Doctrine\ORM\QueryBuilder;
use UserBundle\Entity\UserMailSetting;
use DashboardBundle\Entity\Repository\DashboardRepository;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class UserMailSettingRepository extends DashboardRepository
{
    /**
     * Общая часть запроса для всех других запросов
     * @return QueryBuilder
     */
    private function createQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('q')
            ->addSelect('q, t')
            ->leftJoin('q.translations', 't');
    }

    /**
     * @return UserMailSetting|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getElement(): ?UserMailSetting
    {
        $query = self::createQuery();
        $query->setMaxResults(1);

        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        $query = self::createQuery();

        return $query
            ->where('q.id =:id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $systemName
     * @return UserMailSetting|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getElementBySystemName(string $systemName): ?UserMailSetting
    {
        $query = self::createQuery();
        $query
            ->where('q.systemName= :system_name')
            ->setParameter('system_name', $systemName);

        return $query->getQuery()->getOneOrNullResult();
    }
}
