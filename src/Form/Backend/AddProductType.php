<?php

namespace App\Form\Backend;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix',
            ])
            ->add('weight', IntegerType::class, [
                'label' => 'Poid',
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité',
            ])
            ->add('producer', EntityType::class, [
                'label' => 'Producteur',
                'class' => 'App\Entity\Producer',
            ])
            ->add('subcategory', EntityType::class, [
                'label'    => 'Sous catégories',
                'class'    => 'App\Entity\Subcategory',
                'multiple' => true,
            ])
            ->add('enable', ChoiceType::class, [
                'choices' => [
                    'activé' => true,
                    'désactivé'  => false,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
