<?php

namespace BackendBundle\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use BackendBundle\Entity\Document;

class DocumentRepository extends DashboardRepository
{
	public function getForFrontend()
    {
        return $this->createQueryBuilder('q')
            ->select('q, t')
            ->join('q.translations', 't')
            ->where('q.showOnWebsite = :showOnWebsite')
            ->orderBy('q.position', 'ASC')
            ->setParameters([
                'showOnWebsite' => Document::YES,
            ])
            ->getQuery()
            ->getResult();
    }	
}
