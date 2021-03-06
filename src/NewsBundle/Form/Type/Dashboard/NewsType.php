<?php

namespace NewsBundle\Form\Type\Dashboard;

use NewsBundle\Entity\News;
use NewsBundle\Entity\NewsTag;
use NewsBundle\Entity\NewsAuthor;
use NewsBundle\Entity\NewsElement;
use Doctrine\ORM\EntityRepository;
use NewsBundle\Entity\NewsCategory;
use UploadBundle\Form\Type\UploadType;
use NewsBundle\Entity\NewsGalleryImage;
use Symfony\Component\Form\AbstractType;
use SeoBundle\Form\Type\Dashboard\SeoType;
use Symfony\Component\Security\Core\Security;
use DashboardBundle\Form\Type\DashboardUrlType;
use Symfony\Component\Form\FormBuilderInterface;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardFormType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use SeoBundle\Form\Type\Dashboard\AddSeoSubscriber;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use DashboardBundle\Form\Type\DashboardWYSIWYGType;
use DashboardBundle\Form\Type\DashboardPositionType;
use DashboardBundle\Form\Type\DashboardTextareaType;
use DashboardBundle\Form\Type\DashboardDateTimeType;
use DashboardBundle\Form\Type\DashboardCollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardTranslationsType;
use DashboardBundle\Form\Type\DashboardSelect2EntityType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class NewsType extends AbstractType
{
    /**
     * @var Security
     */
    protected $security;

    /**
     * StaticContentType constructor.
     * @param Security $security
     */
    public function __construct(Security $security, UrlGeneratorInterface $router)
    {
        $this->security = $security;
        $this->router = $router;
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
                $builder
                    ->create('translations', DashboardFormType::class, [
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
                                'field_type' => DashboardTextareaType::class,
                                'label' => 'form.short_description',
                                'required' => false,
                            ],
                            'description' => [
                                'field_type' => DashboardWYSIWYGType::class,
                                'required' => false,
                                'label' => 'Все содержание',
                            ]
                        ],
                        'excluded_fields' => [
                            'id', 'locale', 'translatable', 'createdAt', 'updatedAt', 'treeTitle', 'slug', 'posterAlt', 'signature'
                        ]
                    ])
                    ->add('newsCategory', DashboardSelect2EntityType::class, [
                        'label' => 'Категория',
                        'class' => NewsCategory::class,
                        'choice_label' => 'translate.title',
                    ])
                    ->add('publishAt', DashboardDateTimeType::class, [
                        'label' => 'Дата публикации',
                        'required' => false,
                        'widget' => 'single_text',
                        'html5' => false,
                        'format' => 'dd.MM.yyyy HH:mm'
                    ])
                    ->add(
                        $builder->create('positionShowOnWebsiteIsMain', DashboardFormType::class, [
                            'inherit_data' => true,
                            'useGroupFields' => true,
                            'translation_domain' => null
                        ])
                            ->add('showOnWebsite', DashboardYesNoType::class, [
                                'label' => 'ui.show_on_website',
                                'required' => false,
                                'useFormGroup' => false,
                                'divLg' => 'col-lg-6',
                                'labelLg' => 'col-lg-6'
                            ])
                            ->add('position', DashboardPositionType::class, [
                                'label' => 'ui.position',
                                'required' => false,
                                'useFormGroup' => false,
                                'divLg' => 'col-lg-6',
                                'labelLg' => 'col-lg-6'
                            ])
                    )
            )
            ->add(
                $builder->create('gallery', DashboardFormType::class, [
                    'inherit_data' => true,
                    'tabName' => 'sidebar.photo_gallery.photo_gallery',
                    'translation_domain' => 'DashboardBundle',
                ])
                ->add('galleryImages', DashboardCollectionType::class, [
                    'prototype_template' => '@News/dashboard/news/gallery_images/form/_news_gallery_images_prototype.html.twig',
                    'entry_type' => NewsGalleryImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'by_reference' => false,
                ])
            )
            ->addEventSubscriber(new AddSeoSubscriber())
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => News::class, 'grantedRoles' => null]);
    }
}
