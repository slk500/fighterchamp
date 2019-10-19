<?php

namespace AppBundle\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findAllFighters(): array
    {
        return $this->getEntityManager()->getConnection()
            ->query(' SELECT u.surname, u.name, CONCAT(\'/kluby/\', c.id) as club_href, c.name as club_name, IF(u.male,\'M\',\'K\') as sex,
    TIMESTAMPDIFF(YEAR, u.birth_day, CURDATE()) as age,CONCAT(\'/ludzie/\',u.id) href,
    IFNULL(sum(user_fight.result = \'win\'), 0) AS win,
  IFNULL(sum(user_fight.result = \'draw\'), 0) AS draw,
  IFNULL(sum(user_fight.result = \'lose\' OR user_fight.result = \'disqualified\'), 0) as lose
FROM user u
  LEFT JOIN user_fight ON u.id = user_fight.user_id
  LEFT JOIN club c on u.club_id = c.id
WHERE u.type = 1 OR (u.type = 2 AND user_fight.id is not null) 
GROUP BY u.id')
            ->fetchAll();
    }

    public function findAllFightersRank(): array
    {
        return $this->getEntityManager()->getConnection()
            ->query('SELECT u.surname, u.name, f.formula, f.weight,
  CONCAT(\'/kluby/\', c.id) as club_href, c.name as club_name, IF(u.male,\'M\',\'K\') as sex,
  TIMESTAMPDIFF(YEAR, u.birth_day, CURDATE()) as age,CONCAT(\'/ludzie/\',u.id) href,
  IFNULL(sum(uf.result = \'win\'), 0) AS win,
  IFNULL(sum(uf.result = \'draw\'), 0) AS draw,
  IFNULL(sum(uf.result = \'lose\' OR uf.result = \'disqualified\'), 0) as lose
from fight f
  left join user_fight uf on f.id = uf.fight_id
  left join user u on uf.user_id = u.id
  LEFT JOIN club c on u.club_id = c.id
group by formula, weight, u.id
order by u.male, formula, weight, win desc, draw desc, lose')
            ->fetchAll();
    }

    public function findAllNotSignUpForTournament($tournament)
    {
        $qb = $this->createQueryBuilder('user')
            ->leftJoin('user.signUpTournaments', 'signUpTournament')
            ->andWhere('signUpTournament.tournament = :tournament')
            ->setParameter('tournament', $tournament);

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findAllListAction(int $type)
    {
        $qb = $this->createQueryBuilder('user')
            ->leftJoin('user.userFights', 'userFights')
            ->leftJoin('userFights.fight', 'fights')
            ->leftJoin('userFights.awards', 'awards')
            ->leftJoin('user.club', 'club')
            ->leftJoin('fights.tournament', 'tournament')
            ->addSelect('fights')
            ->addSelect('userFights')
            ->addSelect('awards')
            ->addSelect('club')
            ->andWhere('user.type = :type')
            ->setParameter('type', $type)
            ->setCacheable(true)
//            ->setMaxResults(10)
        ;


        $query = $qb->getQuery();
        return $query->execute();
    }
}
