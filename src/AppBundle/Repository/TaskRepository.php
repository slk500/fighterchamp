<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{
    public function findAllTasks()
    {
        $qb = $this->createQueryBuilder('task')
            ->andWhere('task.finishedAt is not null');

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findAllIdeas()
    {
        $qb = $this->createQueryBuilder('task')
            ->andWhere('task.finishedAt is null');

        $query = $qb->getQuery();
        return $query->execute();
    }
}
