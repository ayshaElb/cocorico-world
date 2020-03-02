<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserUpdateProfilType extends AbstractType
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
            ->add('password', PasswordType::class, [
                'mapped'   => false,
                'required' => false,
                'attr'     => [
                    'placeholder' => 'Laisser vide si inchangé',
                ],
            ])
            ->add('firstname')
            ->add('lastname')
            ->add('telephone')
            ->add('address')
            ->add('postalCode')
            ->add('city')
            ->addEventListener(
                FormEvents::SUBMIT,
                [$this, 'onSubmit'])
            
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

        if ($password) {
            $encodedPassword = $this->encoder->encodePassword($user, $password);
            $user->setPassword($encodedPassword);
        }
    }
}
