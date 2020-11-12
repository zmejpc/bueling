<?php

namespace ComponentBundle\Entity\Repository;

use Gedmo\Loggable\Entity\LogEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class LogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LogEntry::class);
    }

    public function findObjectClasses()
    {
        $query = $this->createQueryBuilder('q');
        $query
            ->select('q.objectClass, COUNT(q.objectClass) AS count')
            ->groupBy('q.objectClass')
            ->indexBy('q', 'q.objectClass');

        return $query->getQuery()->getResult();
    }
}