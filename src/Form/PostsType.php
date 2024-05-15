<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Users;
use App\Entity\Teams;
use App\Entity\Tags;
use App\Entity\Posts;

class PostsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'Résumé',
                'required' => false,
                'attr' => [
                    'class' => 'tinymce',
                    ],
                ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'required' => false,
                'attr' => [
                    'class' => 'tinymce',
                    ],
                ])
            ->add('picture', FileType::class, [
                'data_class' => null,
                'label' => 'Logo',
                'required' => false,
                'help' => 'Fichier jpg, jpeg, png ou webp ne dépassant pas 1 Mo',
                'constraints' => [
                    new File([ 
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'Votre fichier ne doit pas dépasser les 1 M0',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => "Merci de télécharger une image valide.",
                    ]),
                ]
            ])
            ->add('date_add', null, [
                'widget' => 'single_text',
            ])
            ->add('slug', TextType::class, [
                'label' => 'slug',
                'required' => false
            ])
            ->add('fk_tags', EntityType::class, [
                'class' => Tags::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Tags'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
        ]);
    }
}
