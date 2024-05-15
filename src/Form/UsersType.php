<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Users;
use App\Entity\Genders;
use App\Entity\Countries;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => true
                ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required' => true
                ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'required' => true
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'options' => [
                    'attr' => [
                        'class' => 'password-field',
                        'autocomplete' => 'new-password'
                        ]
                    ],
                'constraints' => [
                    new Length([
                        'min' => 10,
                        'max' => 50,
                        'minMessage' => 'Votre mot de passe doit comporter au minimum {{ limit }} caracteres',
                        'maxMessage' => 'Votre mot de passe doit comporter au maximum {{ limit }} characteres',
                    ]),
                    new Regex([
                        'value' => '#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{10,}$#',
                        'message' => 'Le format de votre mot de passe n\'est pas valide'
                    ])
                ],
                'required' => is_null($builder->getData()->getId()) ? true : false,
                'first_options'  => [
                    'label' => 'Mot de passe'
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe'
                ],
                'help' => 'Le mot de passe doit contenir au minimum 10 caractères avec au minimum 1 minuscule, 1 majuscule, 1 chiffre et un caractère spécial parmi @ ! ? * + - _ ~',
                'empty_data' => ''
            ])
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'required' => is_null($builder->getData()->getId()) ? false : true,
                ])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar',
                'required' => false,
                'data_class' => null
                ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'required' => false
                ])
            ->add('zip_code', TextType::class, [
                'label' => 'Code postal',
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 15,
                        'minMessage' => 'Un code postal ne peut pas comporter moins de {{ limit }} caracteres',
                        'maxMessage' => 'Un code postal ne peut pas comporter plus de {{ limit }} characteres',
                        ]),
                    new Regex([
                        'value' => '#[a-zA-Z0-9](-?[a-zA-Z0-9])*$#',
                        'message' => 'Le format de votre code postal n\'est pas valide'
                        ])
                    ]
                ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => false
                ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'required' => false
                ])
            ->add('fk_gender', EntityType::class, [
                'class' => Genders::class,
                'label' => 'Genre',
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('fk_country', EntityType::class, [
                'class' => Countries::class,
                'choice_label' => 'name',
                'label' =>'Pays',
                'required' => false,
                'data' => $options['country']
            ])
            ->add('persona', ChoiceType::class, [
                'mapped' => false,
                'required' => false,
                'choices' =>$options['personas']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'country' => null,
            'personas' => null
        ]);
    }
}
