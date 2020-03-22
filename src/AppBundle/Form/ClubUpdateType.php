<?php

namespace AppBundle\Form;

use AppBundle\Entity\Club;
use AppBundle\Form\EventListener\CreateClubIfNotExist;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ClubUpdateType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, [
                    'disabled' => true]
            )
            ->add('city')
            ->add('street')
            ->add('www')
            ->add('lat', HiddenType::class, [
                'attr' => ['class' => 'lat']
            ])
            ->add('lng', HiddenType::class, [
                'attr' => ['class' => 'lat']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Club::class
        ]);
    }
}
