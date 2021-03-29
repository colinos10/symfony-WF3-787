<?php

namespace App\Form;

use App\Entity\Maison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MaisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Ex.: jolie maison de campagne'
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex.: maison de campagne en bordure de rivière avec grand jardin...'
                ]
            ])
            ->add('surface', IntegerType::class, [
                'required' => true,
                'label' => 'Superficie (m2)',
                'attr' => [
                    'placeholder' => 'Ex.: 100',
                    'min' => 0
                ]
            ])
            ->add('rooms', IntegerType::class, [
                'required' => true,
                'label' => 'Pièces',
                'attr' => [
                    'placeholder' => 'Ex.: 8',
                    'min' => 0
                ]
            ])
            ->add('bedrooms', IntegerType::class, [
                'required' => true,
                'label' => 'Chambres',
                'attr' => [
                    'placeholder' => 'Ex.: 4',
                    'min' => 0
                ]
            ])
            ->add('price', IntegerType::class, [
                'required' => true,
                'label' => 'Prix (€)',
                'attr' => [
                    'placeholder' => 'Ex.: 123 456',
                    'min' => 0
                ]
            ])
            ->add('img1', FileType::class, [
                'required' => false,
                'label' => 'Photo principale',
                'mapped' => false,
                'help' => 'png, jpg, jpeg ou jp2 - 1 Mo maximum',
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/jp2'
                        ],
                        'mimeTypesMessage' => 'Merci de sélectionner une image au format PNG, JPG, JPEG ou JP2'
                    ])
                ]
            ])
            ->add('img2', FileType::class, [
                'required' => false,
                'label' => 'Photo secondaire',
                'mapped' => false,
                'help' => 'png, jpg, jpeg ou jp2 - 1 Mo maximum',
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/jp2'
                        ],
                        'mimeTypesMessage' => 'Merci de sélectionner une image au format PNG, JPG, JPEG ou JP2'
                    ])
                ]
            ])
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Maison::class,
        ]);
    }
}
