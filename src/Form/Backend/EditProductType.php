<?php

namespace App\Form\Backend;

use App\Entity\Product;
use App\Service\FileUploader;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EditProductType extends AbstractType
{
    /** @var FileUploader */
    public $fileUploader;

    /**
     * @param FileUploader $fileUploader
     *
     * @Required
     */
    public function setFileUploader(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

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
            ->add('image', FileType::class, [
                'mapped'      => false,
                'required'    => false,
                'constraints' => [
                    new File([
                        'maxSize'          => '1024k',
                        'mimeTypesMessage' => 'Please upload a valid PNG or JPEG file',
                        'mimeTypes'        => [
                            'image/jpeg',
                            'image/png',
                        ],
                    ]),
                ],
            ])
            ->add('description')
            ->add('composition')
            ->add('additionalInfo', TextareaType::class, [
                'label'    => 'Infos Supp.',
                'required' => false,
            ])
            ->add('allergens', TextType::class, [
                'label'    => 'Allergènes',
                'required' => false,
            ])
            ->add('rate')
            ->add('producer', EntityType::class, [
                'label' => 'Producteur',
                'class' => 'App\Entity\Producer',
            ])
            ->add('subcategory', EntityType::class, [
                'label'    => 'Sous catégories',
                'class'    => 'App\Entity\Subcategory',
            ])
            ->add('enable', ChoiceType::class, [
                'choices' => [
                    'activée' => true,
                    'désactivé'  => false,
                ]
            ])
            ->add('createdAt', DateTimeType::class, [
                'label'          => 'Créer le',
                'view_timezone'  => 'Europe/Paris',
                'date_format'    => 'dd MMM yyyy',
                'with_seconds'   => true,
            ])
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'onPostSubmit']
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }

    public function onPostSubmit(FormEvent $event)
    {
        $product = $event->getData();
        $image = $event->getForm()->get('image')->getNormData();

        if ($image) {
            $product->setImage($this->fileUploader->upload($image));
        }
    }
}
