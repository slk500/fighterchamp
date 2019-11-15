<?php

namespace AppBundle\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class TournamentRepository extends EntityRepository
{
    public function findNewestOne()
    {
        return $this->createQueryBuilder('t')->
        orderBy('t.start', 'DESC')->
        setMaxResults(1)->
        getQuery()->
        getOneOrNullResult();
    }

    public static function createFightsReadyCriteria()
    {
        return Criteria::create()
        ->where(Criteria::expr()->eq('isVisible', true))
        ->orderBy(['day' => 'ASC'])
        ->orderBy(['position' => 'ASC']);
    }

    public static function createSignsUpTournamentNotDeleted()
    {
        return Criteria::create()
        ->where(Criteria::expr()->eq('deleted_at', null))
            ->andWhere(Criteria::expr()->eq('deletedAtByAdmin', null))
            ;
    }

    public function findAllForAdmin()
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t.id, t.name')
        ->orderBy('t.id', 'DESC');


        $query = $qb->getQuery();
        return $query->execute();
    }
}
