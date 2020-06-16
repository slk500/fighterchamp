<?php

namespace AppBundle\Form;

use AppBundle\Entity\Discipline;
use AppBundle\Entity\SparringProposition;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SparringPropositionType extends AbstractType
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
        $fighters = $this->em->getRepository(User::class)->findBy(
            [], ['surname' => 'ASC']);

        $builder
            ->add('opponent', EntityType::class, [
                'class' => User::class,
                'choices' => [
                    'zawodnicy zapisani' => $options['signupUsers'],
                    'zawodnicy niezapisani' => $fighters
                ]
                ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SparringProposition::class,
            'signupUsers' => null
        ]);
    }
}
