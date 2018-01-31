<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 22.04.17
 * Time: 21:49
 */

namespace AppBundle\Serializer\Normalizer;

use AppBundle\Entity\Club;
use Symfony\Component\Routing\Router;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Tests\AppBundle\Serializer\Organization;

class OrganizationNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'name'   => $object->getName(),
            'member' => $this->serializer->normalize($object->getMember(), $format, $context)
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Organization;
    }
}