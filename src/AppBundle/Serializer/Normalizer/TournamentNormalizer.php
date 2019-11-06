<?php

namespace AppBundle\Serializer\Normalizer;

use AppBundle\Entity\Club;
use AppBundle\Entity\Fight;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use AppBundle\Entity\UserFight;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class TournamentNormalizer implements NormalizerInterface, SerializerAwareInterface
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
        return [
            'href' => $this->router->generate('tournament_show', ['id' => $object->getId()]),
            'name'   => $object->getName(),
            'fights' => array_map(function (Fight $fight) {
                return $this->serializer->normalize($fight);
            }, $object->getFights()->toArray())
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Tournament;
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
