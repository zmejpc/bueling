<?php

namespace Ecommerce\Form\Type\Dashboard;

use SeoBundle\Form\Type\Dashboard\SeoType;
use DashboardBundle\Form\Type\DashboardCollectionType;
use DashboardBundle\Form\Type\DashboardWYSIWYGType;
use DashboardBundle\Form\Type\DashboardPositionType;
use DashboardBundle\Form\Type\DashboardSelect2EntityType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\DashboardTextareaType;
use DashboardBundle\Form\Type\DashboardTextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Ecommerce\Entity\ActivityArea;
use Ecommerce\Entity\Project;
use UploadBundle\Form\Type\UploadType;
use DashboardBundle\Form\Type\DashboardTranslationsType;
use Ecommerce\Entity\ProductFeature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Security;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use FrontendBundle\Form\Type\Dashboard\FaqType;
use SeoBundle\Form\Type\Dashboard\AddSeoSubscriber;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ActivityAreaType extends AbstractType
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id = null;
        if ($builder->getData()) {
            $id = $builder->getData()->getId();
        }

        $builder
            ->add('seo', SeoType::class)
            ->add('translations', DashboardTranslationsType::class, [
                'label' => false,
                'fields' => [
                    'title' => [
                        'field_type' => DashboardTextType::class,
                        'label' => 'ui.title',
                        'helpBlock' => null,
                        'maxLength' => 255
                    ],
                    'shortDescription' => [
                        'field_type' => DashboardTextareaType::class,
                        'label' => 'Краткое описание',
                        'required' => false,
                    ],
                    'description' => [
                        'field_type' => DashboardWYSIWYGType::class,
                        'attr' => [
                            'class' => 'tinymce',
                            'data-theme' => 'medium'
                        ],
                        'label' => 'ui.description',
                        'required' => false,
                    ],
                ],
                'excluded_fields' => ['createdAt', 'updatedAt', 'slug']
            ])
            ->add('poster', UploadType::class, [
                'file_type' => 'activity_area_poster',
                'extensions' => '.svg',
                'label' => 'ui.poster',
                'required' => false,
            ])
            ->add('position', DashboardPositionType::class, [
                'label' => 'ui.position',
            ])
            ->add('showOnWebsite', DashboardYesNoType::class, [
                'label' => 'ui.show_on_website',
                'translation_domain' => 'DashboardBundle',
                'required' => false,
            ])
            ->add('showOnHomepage', DashboardYesNoType::class, [
                'label' => 'Отображать на главной?',
                'translation_domain' => 'DashboardBundle',
                'required' => false,
            ])
            ->add('showInFilter', DashboardYesNoType::class, [
                'label' => 'Отображать в фильтре?',
                'translation_domain' => 'DashboardBundle',
                'required' => false,
            ])
            ->add('features', DashboardSelect2EntityType::class, [
                'required' => false,
                'multiple' => true,
                'label' => 'Характеристики',
                'class' => ProductFeature::class,
                'choice_label' => 'translate.title',
            ])
            ->add('galleryImages', DashboardCollectionType::class, [
                'prototype_template' => '@Ecommerce/dashboard/activity_area/form/_activity_area_gallery_images_prototype.html.twig',
                'entry_type' => ActivityAreaGalleryImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ])
            ->add('faq', DashboardCollectionType::class, [
                'prototype_template' => '@Frontend/dashboard/faq/form/_faq_prototype.html.twig',
                'entry_type' => FaqType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ])
            ->add('relatedProjects', DashboardSelect2EntityType::class, [
                'required' => false,
                'multiple' => true,
                'allow_clear' => true,
                'label' => 'Проекты (внизу внутренней страницы)',
                'class' => Project::class,
                'choice_label' => 'translate.title',
            ])
            ->addEventSubscriber(new AddSeoSubscriber())
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => ActivityArea::class, 'grantedRoles' => null]);
    }
}