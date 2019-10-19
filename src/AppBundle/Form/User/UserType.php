<?php


namespace AppBundle\Form\User;

use AppBundle\Entity\Club;
use AppBundle\Entity\User;
use AppBundle\Form\EventListener\AddTermsAndPlainPasswordFieldsIfNewUser;
use AppBundle\Form\EventListener\CreateClubIfNotExist;
use AppBundle\Form\EventListener\CreateCoachIfDosentExist;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
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
            ->add('type', HiddenType::class, [
                'data' => 3])
            ->add('imageName', HiddenType::class)
            ->add(
                'email',
                EmailType::class,
                [
                    'constraints' => [
                        new Email(),
                        new NotBlank()
                    ]
                ]
            )
            ->add('male', ChoiceType::class, [
                'choices' => [
                    'Mężczyzna' => 1,
                    'Kobieta' => 0]
            ])

            ->add('name', TextType::class, [
                'constraints' => [new NotBlank()]
            ])
            ->add('surname', TextType::class, [
                'constraints' => [new NotBlank()]
            ])

            ->add('club', EntityType::class, [
                'required' => false,
                'empty_data'  => null,
                'class' => 'AppBundle:Club',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }])


        ;

        $builder->addEventSubscriber(new CreateClubIfNotExist($this->em));
        ;
        $builder->addEventSubscriber(new AddTermsAndPlainPasswordFieldsIfNewUser());
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class
            ]
        );
    }
}
