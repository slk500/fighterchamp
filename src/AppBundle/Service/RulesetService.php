<?php


namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Util\AgeCategoryConverter;
use Doctrine\ORM\EntityManagerInterface;

class RulesetService
{
    public function getWeights(EntityManagerInterface $em, User $user): array
    {
        $age = AgeCategoryConverter::convert($user->getBirthDay());

        $male = $user->getMale();
        $sex = ($male) ? "male" : "female";

        $traitChoices = $em->getRepository('AppBundle:Ruleset')
            ->findBy([$sex => true, $age => true], ['weight' => 'ASC']);

        $result = [];

        foreach ($traitChoices as $key => $value) {
            $result = $result + [$value->getWeight() => $value->getWeight()];
        }

        return $result;
    }
}
