<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RulesetRepository extends EntityRepository
{
    public function getWeight()
    {
        $qb = $this->createQueryBuilder('rules')
            ->select('rules.weight')
            ->distinct()
            ->orderBy('rules.weight', 'ASC')
        ;

        $query = $qb->getQuery();
        return $query->execute();
    }
}
