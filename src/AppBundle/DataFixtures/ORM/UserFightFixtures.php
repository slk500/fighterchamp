<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Enum\UserFightResult;
use AppBundle\Entity\Fight;
use AppBundle\Entity\User;
use AppBundle\Entity\UserFight;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserFightFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $usersInFight = $this->startArrayFromOne($this->getUsersInFight());

        foreach (range(1, 50) as $i) {
            $userFightResults = $this->faker->boolean() ?
                [UserFightResult::WIN(), UserFightResult::LOSE()] :
                [UserFightResult::LOSE(), UserFightResult::WIN()];

            $userFightOne = new UserFight(
                $this->getReference(User::class . '_' . $usersInFight[$i][0]),
                $this->getReference(Fight::class . '_' . $i)
            );
            $userFightOne->setResult($userFightResults[0]);

            $userFightTwo = new UserFight(
                $this->getReference(User::class . '_' . $usersInFight[$i][1]),
                $this->getReference(Fight::class . '_' . $i)
            );
            $userFightTwo->setResult($userFightResults[1]);

            $manager->persist($userFightOne);
            $manager->persist($userFightTwo);
        }

        $manager->flush();
    }

    public function getUsersInFight()
    {
        return array_map(function () {
            return array_rand(array_flip(range(1, 10)), 2);
        }, range(1, 50));
    }

    public function startArrayFromOne(array $array): array
    {
        return array_filter(array_merge(array(0), $array));
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            FightFixtures::class
        );
    }
}
