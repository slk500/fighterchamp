<?php

namespace AppBundle\Repository;

use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class FightRepository extends EntityRepository
{
    public function findAllFightsForTournamentAdmin($tournament)
    {
        $qb = $this->createQueryBuilder('fight')
            ->addOrderBy('fight.day')
            ->addOrderBy('fight.position')
            ->andWhere('fight.tournament = :tournament')
            ->setParameter('tournament', $tournament)
        ;
        
        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findAllFightByDayAdmin($tournament, $day)
    {
        $qb = $this->createQueryBuilder('fight')
            ->addOrderBy('fight.position')
            ->andWhere('fight.tournament = :tournament')
            ->andWhere('fight.day = :day')
            ->setParameter('tournament', $tournament)
            ->setParameter('day', $day)
        ;

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function fightAllInDayOrderBy($tournament)
    {
        $qb = $this->createQueryBuilder('fight')
            //->addOrderBy('fight.day', 'DESC')
            ->addOrderBy('fight.position')
            ->andWhere('fight.tournament = :tournament')
            //->andWhere('fight.day = :day')
            ->setParameter('tournament', $tournament)
            //->setParameter('day', $day)
        ;

        $query = $qb->getQuery();
        return $query->execute();
    }



    public function fightReadyOrderBy($tournament)
    {
        $qb = $this->createQueryBuilder('fight')
            ->andWhere('fight.isVisible = :isVisible')
            ->andWhere('fight.tournament = :tournament')
            ->setParameter('isVisible', true)
            ->setParameter('tournament', $tournament)
            ->addOrderBy('fight.position')
        ;

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function fightReadyDayOrderBy(Tournament $tournament, $day)
    {
        $qb = $this->createQueryBuilder('fight')
            ->andWhere('fight.isVisible = :isVisible')
            ->andWhere('fight.tournament = :tournament')
            ->andWhere('fight.day = :day')
            ->setParameter('isVisible', true)
            ->setParameter('tournament', $tournament)
            ->setParameter('day', $day)
            ->addOrderBy('fight.position')
        ;

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function setAllFightsReady($tournament)
    {
        $em = $this->getEntityManager();

        $q = $em->createQuery('update AppBundle:Fight fight set fight.is_visible = ?1 where fight.is_visible = ?2 and fight.tournament = ?3')
            ->setParameter(1, true)
            ->setParameter(2, false)
            ->setParameter(3, $tournament);

        $q->execute();
    }


    public function findAllTournamentFightsWhereFightersAreNotWeighted(Tournament $tournament)
    {
        $qb = $this->createQueryBuilder('fight')
            ->leftJoin('fight.signuptournament', 'signuptournament')
            ->andWhere('signuptournament.tournament = :tournament')
            ->andWhere('signuptournament.weighted is null')
            ->setParameter('tournament', $tournament);

        $query = $qb->getQuery();
        return $query->execute();
    }
}
