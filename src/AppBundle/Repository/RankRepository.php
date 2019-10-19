<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Club;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class RankRepository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->connection = $entityManager->getConnection();
    }

    public function findAllByYearAndAge(array $params)
    {
        $addYear = ($params['year'] == '2017' || $params['year'] == '2018' || $params['year'] == '2019');
        $addAge = ($params['age'] == 'junior' || $params['age'] == 'senior');

        $sqlYear = null;
        if ($addYear) {
            $sqlYear = ' where year(f.day) = ? ';
        }

        $sqlAge = null;
        if ($addAge) {
            $sqlAge = ($params['age'] == 'junior') ? ' TIMESTAMPDIFF(YEAR, u.birth_day, CURDATE()) <= 18 ' :
                '  TIMESTAMPDIFF(YEAR, u.birth_day, CURDATE()) > 18 ';
        }

        if ($sqlYear && $sqlAge) {
            $sqlAge = ' and ' . $sqlAge;
        } elseif ($sqlAge) {
            $sqlAge = ' where ' . $sqlAge;
        }


        $stmt = $this->connection->prepare("SELECT u.surname, u.name, f.formula, f.weight,
    CONCAT('/kluby/', c.id) as club_href, c.name as club_name, IF(u.male,'M','K') as sex,
    TIMESTAMPDIFF(YEAR, u.birth_day, CURDATE()) as age,CONCAT('/ludzie/',u.id) href,
    IFNULL(sum(uf.result = 'win' or uf.result = 'win_ko'), 0) AS win,
    IFNULL(sum(uf.result = 'draw'), 0) AS draw,
    IFNULL(sum(uf.result = 'lose' OR uf.result = 'disqualified'), 0) as lose
  from fight f
    left join user_fight uf on f.id = uf.fight_id
    left join user u on uf.user_id = u.id
    LEFT JOIN club c on u.club_id = c.id "
    . $sqlYear . ' ' . $sqlAge .
    "
    group by formula, weight, u.id
    order by u.male, formula, weight, win desc, draw desc, lose");

        if ($sqlYear) {
            $stmt->bindValue(1, $params['year']);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
