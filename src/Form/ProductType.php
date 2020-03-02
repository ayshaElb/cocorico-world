<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Univers;
use App\Entity\Category;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
            'label'=>'Nom du produit'])
            ->add('price', IntegerType::class, [
                'label'=>'Prix du produit'])
            ->add('weight', IntegerType::class, [
                'label'=>'Poids du produit'])
            ->add('quantity', IntegerType::class, [
                'label'=>'Quantité du produit'])
            ->add('description', TextType::class, [
                'label'=>'Description du produit'])
            ->add('composition')
            ->add('additionalInfo', TextType::class, [
                'label'=>'Informations additionnelles']) 
            ->add('allergens', TextType::class, [
                'label'=>'Allergènes du produit']) 
            ->add('subcategories', EntityType::class, [
                'class'       => 'App\Entity\Subcategory',
                'placeholder' => 'Sélectionnez une sous categorie',
                'mapped'      => false,
                'required'    => false
                ])
            ->add('image', FileType::class, [
                'label' => 'Image',

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
            'data_class' => Product::class,
        ]);
    }
}
