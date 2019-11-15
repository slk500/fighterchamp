<?php

namespace AppBundle\Serializer\Normalizer;

use AppBundle\Entity\Task;
use AppBundle\Entity\UserTask;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TaskNormalizer implements NormalizerInterface
{

    /**@var $object Task**/

    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'id' => $object->getId(),
            'description' => $object->getDescription(),
            'www' => $object->getWww(),
            'minutes' => $object->getMinutes(),
            'createdAt' => $object->getCreatedAt()->format('Y-m-d'),
            'usersInventors' => array_map(
                function (UserTask $userTask) {
                    $user = $userTask->getIdea() ? $userTask->getUser(): null;
                    return $user? $user->getName() ." ". $user->getSurname() : null;
                },
                $object->getUserTasks()->toArray()
            ),
            'usersWorkers' => array_map(
                function (UserTask $userTask) {
                    $user = $userTask->getIdea() ? null : $userTask->getUser();
                    return $user? $user->getName() ." ". $user->getSurname() : null;
                },
                $object->getUserTasks()->toArray()
            ),
        ];
    }


    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Task;
    }
}
