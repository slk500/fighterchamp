<?php

namespace AppBundle\Form;

use AppBundle\Entity\Club;
use AppBundle\Entity\Discipline;
use AppBundle\Form\EventListener\CreateClubIfNotExist;
use AppBundle\Form\EventListener\CreateDisciplineIfNotExist;
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

class ClubCreateType extends AbstractType
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
        $club = $builder->getData();

        $builder
            ->add('name', EntityType::class, [
                'constraints' => [new NotBlank()],
                'required' => false,
                'class' => 'AppBundle:Club',
                'mapped' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }])
            ->add('city')
            ->add('street')
            ->add('www')
            ->add('lat', HiddenType::class, [
                'attr' => ['class' => 'lat']
            ])
            ->add('lng', HiddenType::class, [
                'attr' => ['class' => 'lat']
            ])
            ->add('disciplines', EntityType::class, [
                'by_reference' => false,
                'class' => Discipline::class,
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }]);

        $builder->addEventSubscriber(new CreateDisciplineIfNotExist($this->em));

        //shitfx populate data EntityType then change to text
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if (($data['name'])) {
                $form->remove('name');
                $form->add('name');
            }

            $club = $this->em->getRepository(Club::class)->find($data['name']);
            if ($club) {
                $data['name'] = $club->getName();
            }

            $event->setData($data);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Club::class
        ]);
    }
}
