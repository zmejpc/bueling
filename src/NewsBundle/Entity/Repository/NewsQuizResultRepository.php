<?php

namespace NewsBundle\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use Doctrine\ORM\QueryBuilder;

class NewsQuizResultRepository extends DashboardRepository
{
    /**
     * Общая часть запроса для всех других запросов
     * @return QueryBuilder
     */
    private function createQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('q')
            ->select('q, quiz, quizOption')
            ->leftJoin('q.quiz', 'quiz')
            ->leftJoin('q.quizOption', 'quizOption');
    }

    public function getElementByQuizIdAndIp(int $id, string $ip)
    {
        $query = self::createQuery();
        $query
            ->where('quiz.id =:id')
            ->andWhere('q.ip =:ip')
            ->setParameters(['id' => $id, 'ip' => $ip]);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getVotesCntByQuizOptionId(int $id)
    {
        $query = self::createQuery();
        $query
            ->select('count(q.id)')
            ->where('q.quizOption =:id')
            ->setParameter('id', $id);

        return $query->getQuery()->getSingleScalarResult();
    }
}