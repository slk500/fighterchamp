<?php

namespace AppBundle\Serializer\Normalizer;

use AppBundle\Entity\Club;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\User;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SignUpNormalizer implements NormalizerInterface
{
    use CountRecordTrait;

    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        return [
            'id' => $object->getId(),
            'formula'   => $object->getFormula(),
            'weight'   => $object->getFinallWeight(),
            'staz' => $object->getStazTreningowy(),
            'youtubeId' => $object->getYouTubeId(),
            'musicArtistAndTitle' => $object->getMusicArtistAndTitle(),
            'isPaid' => $object->isPaid(),
            'deletedAtByAdmin' => $object->getDeletedAtByAdmin(),
            'weighted' => $object->getWeighted(),
            'trainingTime' => $object->getTrainingTime(),
            'isLicence' => $object->getIsLicence(),
            'discipline' => $object->getDiscipline(),
            'deletedAt' => $object->getDeletedAt(),
            'user'=> [
                'href' => $this->router->generate('user_show', ['id' => $object->getUser()->getId()]),
                'name' => $object->getUser()->getName(),
                'surname' => $object->getUser()->getSurname(),
                'male' => $object->getUser()->getMale(),
                'birthDay' => $object->getUser()->getBirthDay(),
                'record' => $this->countRecord($object->getUser()),
                'club' => $this->club($object->getUser())
            ]
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof SignUpTournament;
    }

    private function club(User $user)
    {
        if (!$user->getClub()) {
            return null;
        }
        return [
            'href' => $this->router->generate('club_show', ['id' => $user->getClub()->getId()]),
            'name' => $user->getClub()->getName(),
        ];
    }
}
