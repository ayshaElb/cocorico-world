<?php

namespace App\Form\Backend;

use App\Entity\Producer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddProducerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('socialReason', TextType::class, [
                'label' => 'Raison sociale',
            ])
            ->add('user', EntityType::class, [
                'label' => 'Utilisateur',
                'class' => 'App\Entity\User',
            ])
            ->add('siretNumber', TextType::class, [
                'label' => 'Numéro de SIRET',
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('postalCode', IntegerType::class, [
                'label' => 'Code postal',
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
            ])
            ->add('email')
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('telephone')
            ->add('enable', CheckboxType::class, [
                'label'    => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Producer::class,
        ]);
    }
}
