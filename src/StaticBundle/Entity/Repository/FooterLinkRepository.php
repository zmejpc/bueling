<?php

namespace StaticBundle\Entity\Repository;

use StaticBundle\Entity\FooterLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FooterLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method FooterLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method FooterLink[]    findAll()
 * @method FooterLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FooterLinkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FooterLink::class);
    }

    // /**
    //  * @return FooterLink[] Returns an array of FooterLink objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FooterLink
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
