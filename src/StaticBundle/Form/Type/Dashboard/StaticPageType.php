<?php

namespace StaticBundle\Form\Type\Dashboard;

use StaticBundle\Entity\StaticPage;
use UploadBundle\Form\Type\UploadType;
use Symfony\Component\Form\AbstractType;
use SeoBundle\Form\Type\Dashboard\SeoType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardFormType;
use SeoBundle\Form\Type\Dashboard\AddSeoSubscriber;
use DashboardBundle\Form\Type\DashboardWYSIWYGType;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use DashboardBundle\Form\Type\DashboardTextareaType;
use DashboardBundle\Form\Type\DashboardCollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardTranslationsType;
use StaticBundle\Form\Type\Dashboard\StaticPageGalleryImageType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class StaticPageType extends AbstractType
{
    /**
     * @var Security
     */
    protected $security;

    /**
     * StaticContentType constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                $builder->create('seo', DashboardFormType::class, [
                    'inherit_data' => true,
                    'tabName' => 'sidebar.configuration.seo',
                    'translation_domain' => 'SeoBundle',
                    'tabIcon' => 'flaticon-stopwatch'
                ])
                    ->add('seo', SeoType::class)
            )
            ->add(
                $builder->create('translations', DashboardFormType::class, [
                    'inherit_data' => true,
                    'tabName' => 'ui.general_info',
                ])
                    ->add('translations', DashboardTranslationsType::class, [
                        'label' => false,
                        'fields' => [
                            'title' => [
                                'field_type' => DashboardTextareaType::class,
                                'label' => 'ui.title',
                                'helpBlock' => null,
                                'maxLength' => 255
                            ],
                            'shortDescription' => [
                                'field_type' => DashboardWYSIWYGType::class,
                                'required' => false,
                                'label' => 'form.short_description',
                            ],
                            'description' => [
                                'field_type' => DashboardWYSIWYGType::class,
                                'required' => false,
                                'label' => 'ui.description',
                            ],
                        ],
                        'excluded_fields' => [
                            'slug', 'id', 'locale', 'translatable', 'createdAt', 'updatedAt', 'treeTitle'
                        ]
                    ])
            )
            ->add(
                $builder->create('galleryImages', DashboardFormType::class, [
                    'inherit_data' => true,
                    'tabName' => 'sidebar.photo_gallery.photo_gallery',
                    'translation_domain' => 'DashboardBundle',
                ])
                    ->add('galleryImages', DashboardCollectionType::class, [
                        'prototype_template' => '@Ecommerce/dashboard/product/form/_product_gallery_images_prototype.html.twig',
                        'entry_type' => StaticPageGalleryImageType::class,
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'by_reference' => false,
                    ])
            )
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));

        if ($this->security->isGranted('ROLE_DEVELOPER')) {
            $builder->get('translations')
                ->add('systemName', DashboardTextType::class, [
                    'helpBlock' => null,
                    'maxLength' => 255,
                    'label' => 'form.system_name'
                ]);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => StaticPage::class, 'grantedRoles' => null]);
    }
}
