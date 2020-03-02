<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationType extends AbstractType
{
    /** @var UserPasswordEncoderInterface $encoder */
    private $encoder;

    /**
     * @Required
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function setPasswordEncoder(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('password', RepeatedType ::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identique !',
                'mapped'          => false,
                'options'         => [
                    'attr' => [
                        'class' => 'password-field'
                    ]
                ],
                'required'       => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répéter votre mot de passe']
            ])
            ->addEventListener(
                FormEvents::SUBMIT,
                [$this, 'onSubmit']
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ],
        ]);
    }

    public function onSubmit(FormEvent $event)
    {
        /** @var User $user */
        $user = $event->getData();

        $form = $event->getForm();
        $password = $form->get('password')->getNormData();

        $encodedPassword = $this->encoder->encodePassword($user, $password);
        $user->setPassword($encodedPassword);
    }
}
