<?php

namespace App\Form\Backend;

use App\Entity\User;
use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditUserType extends AbstractType
{
    /** @var UserPasswordEncoderInterface */
    public $encoder;

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
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'choices' => [
                    'Utilisateur'    => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                    'producteur'     => 'ROLE_PRODUCER',
                ],
            ])
            ->add('password', PasswordType::class, [
                'mapped'   => false,
                'required' => false,
                'label'    => 'Mot de passe',
                'attr'     => [
                    'placeholder' => 'Laisser vide si inchangé'
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('telephone')
            ->add('address', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('postalCode', IntegerType::class, [
                'label' => 'Code postal',
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
            ])
            ->add('enable', CheckboxType::class, [
                'label'    => false,
                'required' => false,
            ])
            ->add('createdAt', DateTimeType::class, [
                'label'          => 'Créer le',
                'view_timezone'  => 'Europe/Paris',
                'date_format'    => 'dd MMM yyyy',
                'with_seconds'   => true,
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
