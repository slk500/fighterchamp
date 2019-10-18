<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Club;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setHash($this->faker->sha1);
        $admin->setEmail('admin@admin.pl');
        $admin->setName('admin');
        $admin->setSurname('admin');
        $admin->setMale(true);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPlainPassword('password');

        $manager->persist($admin);

        $user = new User();
        $user->setHash($this->faker->sha1);
        $user->setEmail('user@user.pl');
        $user->setName('user');
        $user->setSurname('user');
        $user->setMale(true);
        $user->setRoles(['ROLE_USER']);
        $user->setPlainPassword('password');

        $manager->persist($user);

        foreach (range(1, 10) as $i) {
            $user = new User();
            $user->setHash($this->faker->sha1);
            $user->setEmail($this->faker->email);
            $user->setName($this->faker->firstName);
            $user->setSurname($this->faker->lastName);
            $user->setMale($this->faker->boolean());
            $user->setClub($this->getReference(Club::class . '_' . rand(1, 2)));
            $manager->persist($user);

            $this->addReference(User::class . '_' . $i, $user);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          ClubFixtures::class
        ];
    }
}
