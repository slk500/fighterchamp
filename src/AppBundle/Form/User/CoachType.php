<?php

namespace AppBundle\Form\User;

use AppBundle\Form\EventListener\CreateCoachIfDosentExist;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CoachType extends AbstractType
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
        $user = $builder->getData();

        $builder
            ->add('type', HiddenType::class, [
                'data' => 2])
            ->add('imageName', HiddenType::class)
            ->add('birthDay', BirthdayType::class, [
                'translation_domain' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('phone', TextType::class, [
                'constraints' => [new NotBlank()]
            ])
            ->add('coachId', EntityType::class, [
                'required' => false,
                'empty_data' => null,
                'mapped' => false,
                'class' => 'AppBundle:User',
                'data' => $user ? $user->getCoaches()->first() : null,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.type = 2')
                        ->orderBy('u.name', 'ASC');
                }])
            ->add('pesel', TextType::class, ['label' => 'Pesel']);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
    }


    public function getParent()
    {
        return UserType::class;
    }
}
