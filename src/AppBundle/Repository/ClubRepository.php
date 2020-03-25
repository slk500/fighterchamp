<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Club;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class ClubRepository
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Club::class);
        $this->entityManager = $entityManager;
    }

    public function find(int $id): Club
    {
        return $this->repository->find($id);
    }

    public function findAll(): array
    {
        return $this->entityManager->getConnection()
            ->query('SELECT CONCAT(\'/kluby/\', c.id) as href, c.name,c.city, c.street, c.lat, c.lng, 
                    count(distinct(u.id)) as user_count, club_disc.disciplines
  ,sum(case when uf.result = \'win\' then 1 else 0 end) as win
  ,sum(case when uf.result = \'draw\' then 1 else 0 end) as draw
  ,sum(case when uf.result = \'lose\' or uf.result = \'disqualified\' then 1 else 0 end) as lose
FROM club c
  LEFT JOIN user u ON u.club_id = c.id
  LEFT JOIN user_fight AS uf ON uf.user_id = u.id
  LEFT JOIN (SELECT club_id, GROUP_CONCAT(name SEPARATOR \', \') AS disciplines
            FROM club_dict_discipline cdd
            LEFT JOIN dict_discipline dd on cdd.dict_discipline_id = dd.id) club_disc on club_disc.club_id = c.id
group by c.id;')
            ->fetchAll();
    }
}
