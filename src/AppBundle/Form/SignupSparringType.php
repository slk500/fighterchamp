<?php

namespace AppBundle\Form;

use AppBundle\Entity\Discipline;
use AppBundle\Entity\SignupSparring;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SignupSparringType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('trainingTime', IntegerType::class, array(
                'scale' => 1,
                'attr' => [
                    'min' => 0,
                    'max' => 1,
                    'step' => '.1',
                ],
            ))
            ->add('weight', IntegerType::class,[
                'constraints' => [new NotBlank()]
                ])
            ->add('discipline', EntityType::class, [
                'class' => Discipline::class,
                'constraints' => [new NotBlank()],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }])
           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SignupSparring::class,
            'user_id' => null,
        ]);
    }
}
