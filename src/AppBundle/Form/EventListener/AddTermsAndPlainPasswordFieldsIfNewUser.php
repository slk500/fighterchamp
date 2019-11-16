<?php

namespace AppBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\IsTrue;

class AddTermsAndPlainPasswordFieldsIfNewUser implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $user = $event->getData();

        if (!$user || null === $user) {
            $form = $event->getForm();

            $form->add(
                'plain_password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class
                ]
            )
                ->add('terms', CheckboxType::class, array(
                    'constraints'=>new IsTrue(array('message'=>'Aby się zarejestrować musisz zaakceptować regulamin')),
                    'mapped' => false,
                    'label' => ''))
            ;
        }
    }
}
