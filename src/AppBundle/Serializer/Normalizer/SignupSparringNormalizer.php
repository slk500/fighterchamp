<?php

namespace AppBundle\Serializer\Normalizer;

use AppBundle\Entity\SignupSparring;
use AppBundle\Entity\User;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SignupSparringNormalizer implements NormalizerInterface
{
    use CountRecordTrait;

    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        return [
            'weight' => $object->weight,
            'trainingTime' => $object->trainingTime,
            'discipline' => $object->discipline,
            'user'=> [
                'href' => $this->router->generate('view_user_show', ['id' => $object->user->getId()]),
                'name' => $object->user->getName(),
                'surname' => $object->user->getSurname(),
                'male' => $object->user->getMale(),
                'birthDay' => $object->user->getBirthDay(),
                'record' => $this->countRecord($object->user),
                'club' => $this->club($object->user)
            ]
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof SignupSparring;
    }

    private function club(User $user)
    {
        if (!$user->getClub()) {
            return null;
        }
        return [
            'href' => $this->router->generate('view_club_show', ['id' => $user->getClub()->getId()]),
            'name' => $user->getClub()->getName(),
        ];
    }
}
