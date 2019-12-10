<?php

namespace AppBundle\Serializer\Normalizer;

use AppBundle\Entity\Award;
use AppBundle\Entity\Fight;
use AppBundle\Entity\User;
use AppBundle\Entity\UserFight;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements NormalizerInterface
{
    use CountRecordTrait;

    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param User $object
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return [
            'id' => $object->getId(),
            'href' => $this->router->generate('view_user_show', ['id' => $object->getId()]),
            'name' => $object->getName(),
            'surname' => $object->getSurname(),
            'male' => $object->getMale(),
            'birthDay' => $object->getBirthDay(),
            'imageName' => $object->getImageName(),
            'record' => $this->countRecord($object),
            'club' => $this->club($object),
            'coaches' => $this->coach($object),
            'type' => $object->getType(),
            'fighters' => array_map(
                function (User $user) {
                    return [
                        'href' => $this->router->generate('view_user_show', ['id' => $user->getId()]),
                        'name' => $user->getName(),
                        'surname' => $user->getSurname(),
                        'male' => $user->getMale(),
                        'birthDay' => $user->getBirthDay(),
                        'record' => $this->countRecord($user),
                        'club' => $this->club($user),
                        'type' => $user->getType(),
                    ];
                },
                $object->getFighters()->toArray()
            ),
            'fightersRecord' => $this->getFightersRecord($object),
            'fights' => array_map(
                function (Fight $fight) {
                    return [
                        'href' => $this->router->generate('fight_show', ['id' => $fight->getId()]),
                        'formula' => $fight->getFormula(),
                        'weight' => $fight->getWeight(),
                        'youtubeId' => $fight->getYoutubeId(),
                        'tournament' => [
                            'href' => $this->router->generate('tournament_show', ['id' => $fight->getTournament()->getId()]),
                            'name' => $fight->getTournament()->getName()
                        ],
                        'usersFight' => array_map(
                            function (UserFight $userFight) {
                                return [
                                    'isRedCorner' => $userFight->isRedCorner(),
                                    'result' => $userFight->getResult(),
                                    'awards' => array_map(
                                        function (Award $award) {
                                            return [
                                                'name' => $award->getName(),
                                            ];
                                        },
                                        $userFight->getAwards()->toArray()
                                    ),
                                    'user' => [
                                        'href' => $this->router->generate('view_user_show', ['id' => $userFight->getUser()->getId()]),
                                        'name' => $userFight->getUser()->getName(),
                                        'surname' => $userFight->getUser()->getSurname(),
                                        'male' => $userFight->getUser()->getMale(),
                                        'birthDay' => $userFight->getUser()->getBirthDay(),
                                        'record' => $this->countRecord($userFight->getUser()),
                                        'club' => $this->club($userFight->getUser()),
                                        'type' => $userFight->getUser()->getType(),
                                    ]
                                ];
                            },
                            $fight->getUsersFight()->toArray()
                        )
                    ];
                },
                $object->getFights()->toArray()
            )
        ];
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

    private function coach(User $user)
    {
        if ($user->getCoaches()->isEmpty()) {
            return null;
        }

        return $user->getCoaches()->map(function (User $coach) {
            return [
                'href' => $this->router->generate('view_user_show', ['id' => $coach->getId()]),
                'name' => $coach->getName(),
                'surname' => $coach->getSurname()
            ];
        });
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof User;
    }

    private function getFightersRecord(User $object)
    {
        $result = [
            'win' => 0,
            'draw' => 0,
            'lose' => 0
        ];


        foreach ($object->getFighters() as $user) {
            $temp = $this->countRecord($user);

            $result['win'] += $temp['win'];
            $result['draw'] += $temp['draw'];
            $result['lose'] += $temp['lose'];
        }

        return $result;
    }
}
