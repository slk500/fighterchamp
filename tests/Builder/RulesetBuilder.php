<?php

namespace Tests\Builder;

use AppBundle\Entity\Ruleset;

class RulesetBuilder extends Builder
{
    public function build(): Ruleset
    {
        $ruleset = new Ruleset();
        $ruleset->setMale(true);
        $ruleset->setFemale(false);
        $ruleset->setSenior(true);
        $ruleset->setJunior(false);
        $ruleset->setKadet(false);
        $ruleset->setWeight(100);

        return $ruleset;
    }
}
