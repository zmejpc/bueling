<?php

namespace StaticBundle\Entity\Repository;

use StaticBundle\Entity\FooterImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FooterImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method FooterImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method FooterImage[]    findAll()
 * @method FooterImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FooterImageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FooterImage::class);
    }

    // /**
    //  * @return FooterImage[] Returns an array of FooterImage objects
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
    public function findOneBySomeField($value): ?FooterImage
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
