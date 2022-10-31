<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class AuthType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-posta',
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Lütfen e-posta adresinizi giriniz.',
                    ]),
                    new Assert\Email([
                        'message' => 'Lütfen geçerli bir e-posta adresi giriniz.',
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'required' => false,
                ),
                // option value ile option label aynı değerleri alıyor
                'choices' => [
                    'Seçim Yapınız' => null,
                    'admin' => 'ROLE_ADMIN',
                    'user' => 'ROLE_USER',
                ],
                'mapped' => false,

                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Lütfen bir rol seçiniz.',
                    ]),
                    // sıfıra eşit olmasın
                    new Assert\NotEqualTo([
                        'value' => 0,
                        'message' => 'Lütfen bir rol seçiniz.',
                    ]),
                ],
            ))
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => [
                        'label' => false,
                    ],
                    'second_options' => [
                        'label' => false,
                    ],
                    'constraints' => [
                        // değerleri eşit olmalı
                        new Assert\NotBlank([
                            'message' => 'Lütfen şifrenizi giriniz.',
                        ]),
                        new Assert\Length([
                            'min' => 6,
                            'minMessage' => 'Şifreniz en az {{ limit }} karakter olmalıdır.',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
