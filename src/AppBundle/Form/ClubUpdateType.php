<?php

namespace AppBundle\Form;

use AppBundle\Entity\Club;
use AppBundle\Entity\Discipline;
use AppBundle\Form\EventListener\CreateDisciplineIfNotExist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('name', TextType::class, [
                'disabled' => true])
            ->add('city')
            ->add('street')
            ->add('www')
            ->add('disciplines', EntityType::class, [
                'by_reference' => false,
                'class' => Discipline::class,
                'multiple' => true])
            ->add('lat', HiddenType::class, [
                'attr' => ['class' => 'lat']
            ])
            ->add('lng', HiddenType::class, [
                'attr' => ['class' => 'lat']
            ]);

        $builder->addEventSubscriber(new CreateDisciplineIfNotExist($this->em));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Club::class
        ]);
    }
}
