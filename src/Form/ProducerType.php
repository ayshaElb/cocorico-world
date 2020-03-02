<?php

namespace App\Form;

use App\Entity\Producer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProducerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('socialReason', TextType::class, [
                'label' => 'Raison sociale '
            ])
            ->add('siretNumber', TextType::class, [
                'label' => 'Numero de siret '
            ])
            ->add('address',TextType::class, [
                'label' => 'Adresse '
            ])
            ->add('postalCode', IntegerType::class, [
                'label' => 'Code postale '
            ])
            ->add('city',TextType::class, [
                'label' => 'Ville '
            ])
            ->add('email',TextType::class, [
                'label' => 'Email '
            ])
            ->add('firstname',TextType::class, [
                'label' => 'Prénom '
            ])
            ->add('lastname',TextType::class, [
                'label' => 'Nom '
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone '
            ])
            ->add('status', TextType::class, [
                'label' => 'Status '
            ])
            ->add('description', TextType::class, [
                'label' => 'Description '
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Producer::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ],
        ]);
    }
}
