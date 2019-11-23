<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Ruleset;
use Doctrine\Common\Persistence\ObjectManager;

class RulesetFixtures extends BaseFixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $ruleData) {
            $rule = new Ruleset();
            $rule->setWeight($ruleData['weight']);
            $rule->setFemale($ruleData['female']);
            $rule->setMale($ruleData['male']);
            $rule->setJunior($ruleData['junior']);
            $rule->setSenior($ruleData['senior']);
            $rule->setKadet($ruleData['kadet']);
            $manager->persist($rule);
        }
        $manager->flush();
    }

    private function getData()
    {
        return [
            0 =>
            [
                'id' => 1,
                'weight' => '46',
                'male' => 1,
                'female' => 0,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 1,
            ],
            1 =>
            [
                'id' => 2,
                'weight' => '48',
                'male' => 0,
                'female' => 1,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 1,
            ],
            2 =>
            [
                'id' => 3,
                'weight' => '48',
                'male' => 1,
                'female' => 0,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            3 =>
            [
                'id' => 4,
                'weight' => '49',
                'male' => 1,
                'female' => 0,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 0,
            ],
            4 =>
            [
                'id' => 5,
                'weight' => '50',
                'male' => 1,
                'female' => 0,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            5 =>
            [
                'id' => 6,
                'weight' => '51',
                'male' => 0,
                'female' => 1,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 0,
            ],
            6 =>
            [
                'id' => 7,
                'weight' => '52',
                'male' => 1,
                'female' => 0,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 1,
            ],
            7 =>
            [
                'id' => 8,
                'weight' => '54',
                'male' => 1,
                'female' => 0,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            8 =>
            [
                'id' => 9,
                'weight' => '54',
                'male' => 0,
                'female' => 1,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 1,
            ],
            9 =>
            [
                'id' => 10,
                'weight' => '57',
                'male' => 1,
                'female' => 0,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            10 =>
            [
                'id' => 11,
                'weight' => '57',
                'male' => 0,
                'female' => 1,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 1,
            ],
            11 =>
            [
                'id' => 12,
                'weight' => '60',
                'male' => 1,
                'female' => 1,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 1,
            ],
            12 =>
            [
                'id' => 13,
                'weight' => '63',
                'male' => 1,
                'female' => 0,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            13 =>
            [
                'id' => 14,
                'weight' => '64',
                'male' => 1,
                'female' => 0,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 0,
            ],
            14 =>
            [
                'id' => 15,
                'weight' => '64',
                'male' => 0,
                'female' => 1,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 0,
            ],
            15 =>
            [
                'id' => 16,
                'weight' => '66',
                'male' => 1,
                'female' => 0,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            16 =>
            [
                'id' => 17,
                'weight' => '69',
                'male' => 1,
                'female' => 0,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 0,
            ],
            17 =>
            [
                'id' => 18,
                'weight' => '69',
                'male' => 0,
                'female' => 1,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 0,
            ],
            18 =>
            [
                'id' => 19,
                'weight' => '70',
                'male' => 1,
                'female' => 1,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            19 =>
            [
                'id' => 20,
                'weight' => '75',
                'male' => 1,
                'female' => 1,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 1,
            ],
            20 =>
            [
                'id' => 22,
                'weight' => '80',
                'male' => 1,
                'female' => 0,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            21 =>
            [
                'id' => 23,
                'weight' => '80',
                'male' => 0,
                'female' => 1,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 1,
            ],
            22 =>
            [
                'id' => 24,
                'weight' => '80+',
                'male' => 1,
                'female' => 0,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            23 =>
            [
                'id' => 25,
                'weight' => '80+',
                'male' => 0,
                'female' => 1,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 1,
            ],
            24 =>
            [
                'id' => 26,
                'weight' => '91',
                'male' => 1,
                'female' => 0,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 0,
            ],
            25 =>
            [
                'id' => 27,
                'weight' => '91+',
                'male' => 1,
                'female' => 0,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 0,
            ],
            26 =>
            [
                'id' => 28,
                'weight' => '81',
                'male' => 1,
                'female' => 0,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 0,
            ],
            27 =>
            [
                'id' => 29,
                'weight' => '56',
                'male' => 1,
                'female' => 0,
                'junior' => 1,
                'senior' => 1,
                'kadet' => 0,
            ],
            28 =>
            [
                'id' => 30,
                'weight' => '44',
                'male' => 1,
                'female' => 1,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            29 =>
            [
                'id' => 31,
                'weight' => '46',
                'male' => 0,
                'female' => 1,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            30 =>
            [
                'id' => 32,
                'weight' => '50',
                'male' => 0,
                'female' => 1,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            31 =>
            [
                'id' => 33,
                'weight' => '52',
                'male' => 0,
                'female' => 1,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            32 =>
            [
                'id' => 34,
                'weight' => '63',
                'male' => 0,
                'female' => 1,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            33 =>
            [
                'id' => 35,
                'weight' => '66',
                'male' => 0,
                'female' => 1,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            34 =>
            [
                'id' => 36,
                'weight' => '43',
                'male' => 1,
                'female' => 1,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            35 =>
            [
                'id' => 37,
                'weight' => '41,5',
                'male' => 1,
                'female' => 1,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            36 =>
            [
                'id' => 38,
                'weight' => '40',
                'male' => 1,
                'female' => 1,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
            37 =>
            [
                'id' => 39,
                'weight' => '38,5',
                'male' => 1,
                'female' => 1,
                'junior' => 0,
                'senior' => 0,
                'kadet' => 1,
            ],
        ];
    }
}
