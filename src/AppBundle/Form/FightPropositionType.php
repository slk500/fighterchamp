<?php

namespace AppBundle\Form;

use AppBundle\Entity\Discipline;
use AppBundle\Entity\FightProposition;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FightPropositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('opponent', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.surname', 'ASC');
                }])
            ->add('discipline', EntityType::class, [
                'constraints' => [new NotBlank()],
                'class' => Discipline::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }])
            ->add('formula', ChoiceType::class, [
                'placeholder' => 'Nie dotyczy',
                'choices' => [
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C'
                ]])
            ->add('weight', TextType::class, [
                'constraints' => [new NotBlank()]
            ])
            ->add('weight', TextType::class, [
                'constraints' => [new NotBlank()]
            ])
            ->add('result', ChoiceType::class, [
                'constraints' => [new NotBlank()],
                'choices' => [
                    'Wygrana' => 'win',
                    'Wygrana przez KO' => 'win_ko',
                    'Wygrana przez dyskwalfikację przeciwnika' => 'win_disqualify',
                    'Remis' => 'draw',
                    'Przegrana' => 'lose',
                    'Przegrana przez KO' => 'lose_ko',
                    'Przegrana przez dyskwalifikacje' => 'lose_disqualify',
                ]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FightProposition::class
        ]);
    }
}
