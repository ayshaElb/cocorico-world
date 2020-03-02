<?php

namespace App\Form\Backend;

use App\Entity\Subcategory;
use App\Service\FileUploader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EditSubcategoryType extends AbstractType
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
            ->add('image', FileType::class, [
                'mapped'      => false,
                'required'    => false,
                'constraints' => [
                    new File([
                        'maxSize'          => '1024k',
                        'mimeTypesMessage' => 'Please upload a valid PNG or JPEG file',
                        'mimeTypes'        => [
                            'image/png',
                            'image/jpeg',
                        ],
                    ]),
                ],
            ])
            ->add('category')
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
                FormEvents::POST_SUBMIT,
                [$this, 'onPostSubmit']
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subcategory::class,
        ]);
    }

    public function onPostSubmit(FormEvent $event)
    {
        $subcategory = $event->getData();
        $image = $event->getForm()->get('image')->getNormData();

        if ($image) {
            $subcategory->setImage($this->fileUploader->upload($image));
        }
    }
}
