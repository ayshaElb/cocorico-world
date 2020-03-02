<?php

namespace App\Form\Backend;

use App\Entity\User;
use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AddUserType extends AbstractType
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /**
     * @param UserPasswordEncoderInterface $encoder
     *
     * @Required
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
                'label' => 'Mot de passe',
            ])
            ->add('enable', CheckboxType::class, [
                'label'    => false,
                'required' => false,
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
        ]);
    }

    public function onSubmit(FormEvent $event)
    {
        $user = $event->getData();
        $encodedPassword = $this->encoder->encodePassword($user, $user->getPassword());

        $user->setPassword($encodedPassword);
    }
}
