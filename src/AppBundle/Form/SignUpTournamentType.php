<?php

namespace AppBundle\Form;

use AppBundle\Entity\SignUpTournament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignUpTournamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $trait = $options['trait_choices'];

        $builder
            ->add('formula', ChoiceType::class, [
                'choices'  => [
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C'
                ]])
            ->add('trainingTime', IntegerType::class)
            ->add('weight', ChoiceType::class, [
                'choices'  => $trait
            ])
            ->add('discipline', ChoiceType::class, [
                'choices'  => [
                    'BJJ' => 'BJJ',
                    'Boks' => 'Boks',
                    'MMA' => 'MMA',
                    'Karate' => 'Karate',
                    'K1 ProAm' => 'K1 ProAm'
                ]])
//            ->add('isLicence', CheckboxType::class)
//            ->add('youtubeId', TextType::class)
//            ->add('musicArtistAndTitle', TextType::class)
           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SignUpTournament::class,
            'trait_choices' => null,
            'user_id' => null,
            'csrf_protection'   => false,
        ]);
    }
}
