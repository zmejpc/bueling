<?php

namespace BackendBundle\Entity\Repository;

use BackendBundle\Entity\SliderImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SliderImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method SliderImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method SliderImage[]    findAll()
 * @method SliderImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SliderImageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SliderImage::class);
    }

    // /**
    //  * @return SliderImage[] Returns an array of SliderImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SliderImage
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
