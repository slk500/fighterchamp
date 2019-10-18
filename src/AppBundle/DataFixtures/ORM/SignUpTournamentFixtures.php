<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SignUpTournamentFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach (range(1, 10) as $i) {
            foreach (range(1, 10) as $j) {
                $signUpTournament = new SignUpTournament(
                    $this->getReference(User::class . '_' . $i),
                    $this->getReference(Tournament::class . '_' . $j)
                );
                $signUpTournament->setWeight($this->faker->numberBetween(50, 100));
                $signUpTournament->setFormula($this->faker->randomElement(['A', 'B', 'C']));

                $manager->persist($signUpTournament);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            TournamentFixtures::class
        );
    }
}
