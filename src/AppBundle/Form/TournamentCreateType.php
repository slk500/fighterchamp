<?php

namespace AppBundle\Form;

use AppBundle\Entity\Club;
use AppBundle\Entity\Discipline;
use AppBundle\Entity\Tournament;
use AppBundle\Form\EventListener\CreateDisciplineIfNotExist;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class TournamentCreateType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tournament = $builder->getData();

        $builder
            ->add('name', EntityType::class, [
                'constraints' => [new NotBlank()],
                'required' => false,
                'class' => Tournament::class,
                'mapped' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.isEditable = true')
                        ->orderBy('u.name', 'ASC');
                }])
            ->add('disciplines', EntityType::class, [
                'constraints' => [new NotBlank()],
                'by_reference' => false,
                'class' => Discipline::class,
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }])
            ->add('start', DateType::class, [
                'translation_domain' => true,
                'years' => range((int) date('Y') - 30, (int) date('Y') + 5)
            ])
            ->add('end', DateType::class, [
                'translation_domain' => true,
                'years' => range((int) date('Y') - 30, (int) date('Y') + 5)
            ])
            ->add('place', TextType::class)
            ->add('city', TextType::class)
            ->add('street', TextType::class)

        ;

        $builder->addEventSubscriber(new CreateDisciplineIfNotExist($this->em));

        //shitfx populate data EntityType then change to text
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if (($data['name'])) {
                $form->remove('name');
                $form->add('name');
            }

            $tournament = $this->em->getRepository(Club::class)->find($data['name']);
            if ($tournament) {
                $data['name'] = $tournament->getName();
            }

            $event->setData($data);
        });
    }
}
