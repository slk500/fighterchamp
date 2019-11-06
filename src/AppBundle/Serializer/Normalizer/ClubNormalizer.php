<?php

namespace AppBundle\Serializer\Normalizer;

use AppBundle\Entity\Club;
use AppBundle\Entity\User;
use AppBundle\Entity\UserFight;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class ClubNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use CountRecordTrait;
    use SerializerAwareTrait;

    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function normalize($object, $format = null, array $context = array())
    {
        /**
         * @var $object Club
         */

        return [
            'href' => $this->router->generate('club_show', ['id' => $object->getId()]),
            'name'   => $object->getName(),
            'www' => $object->getWww(),
            'city' => $object->getCity(),
            'record' => $this->countRecordClub($object->getUsers()),
            'users' => $this->serializer->normalize($object->getUsers(), $format, $context)
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Club;
    }

    private function countRecordClub($users)
    {
        $win = $draw = $lose = 0;

        foreach ($users as $user) {
            $record =  $this->countRecord($user);

            $win += $record['win'];
            $draw += $record['draw'];
            $lose += $record['lose'];
        }

        return [
            'win' => $win,
            'draw' => $draw,
            'lose' => $lose
        ];
    }
}
