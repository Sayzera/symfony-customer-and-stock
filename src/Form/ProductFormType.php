<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Images;
use App\Entity\Products;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class ProductFormType extends AbstractType
{
    // entty manage
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // ekstra resim dosyası ekle 


        /**
         * ilişkisel tablo olduğu için entity clasını __toString() metodunu override ederek kullanıyoruz
         */
        $builder
            ->add('name', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ürün Adı'
                ]
            ])
            ->add('price', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ürün Fiyatı',
                ]
            ])
            ->add('description', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ürün Açıklaması',
                ]
            ])
            // selectbox
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => function (Categories $category) {
                    // return sprintf('(%d) %s', $category->getId(), $category->getName());
                    return $category->getName();
                },
                'placeholder' => 'Kategori Seçiniz',
                'label' => false
            ])
            // add images
            ->add('images', FileType::class, [
                'label' => false,
                'mapped' => false,
                'multiple' => true,
                'required' => false,
                'constraints' => [
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '1024k',
                                'mimeTypesMessage' => 'Lütfen geçerli bir resim dosyası seçiniz',
                                'mimeTypes' => [
                                    'image/*',
                                ]
                            ]),
                        ],
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,

        ]);
    }
}
