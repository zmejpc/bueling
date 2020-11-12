<?php

namespace NewsBundle\Form\Type\Dashboard;

use NewsBundle\Entity\NewsElement;
use UploadBundle\Form\Type\UploadType;
use Symfony\Component\Form\AbstractType;
use DashboardBundle\Form\Type\DashboardUrlType;
use Symfony\Component\Form\FormBuilderInterface;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\DashboardWYSIWYGType;
use DashboardBundle\Form\Type\DashboardPositionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardTranslationsType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class NewsElementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', DashboardTranslationsType::class, [
                'label' => false,
                'fields' => [
                    'title' => [
                        'field_type' => DashboardTextType::class,
                        'label' => 'Подпись к фотографии',
                        'translation_domain' => 'NewsBundle',
                        'helpBlock' => null,
                        'maxLength' => 255
                    ],
                    'description' => [
                        'field_type' => DashboardWYSIWYGType::class,
                        'required' => false,
                        'label' => 'text_under_the_photo',
                        'translation_domain' => 'NewsBundle',
                    ]
                ],
                'excluded_fields' => [
                    'id', 'locale', 'translatable', 'createdAt', 'updatedAt', 'treeTitle'
                ]
            ])
            ->add('link', DashboardUrlType::class, [
                'label' => 'ui.link',
                'required' => false,
                'maxLength' => 255
            ])
            ->add('position', DashboardPositionType::class, [
                'label' => 'ui.position',
                'required' => false,
                'divLg' => 'col-lg-8',
                'labelLg' => 'col-lg-4'
            ])
            ->add('showOnWebsite', DashboardYesNoType::class, [
                'label' => 'ui.show_on_website',
                'required' => false,
                'divLg' => 'col-lg-6',
                'labelLg' => 'col-lg-6'
            ])
            ->add('img', UploadType::class, [
                'file_type' => 'news_item_gallery',
                'extensions' => '.jpg, .gif, .png, .svg',
                'label' => 'ui.image',
                'template' => '@News/dashboard/news/gallery_images/form/_upload.html.twig',
                'required' => false,
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => NewsElement::class]);
    }
}