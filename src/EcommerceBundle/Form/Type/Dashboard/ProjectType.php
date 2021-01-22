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
use DashboardBundle\Form\Type\DashboardDateTimeType;
use Doctrine\ORM\EntityRepository;
use BackendBundle\Entity\Region;
use Ecommerce\Entity\Project;
use DashboardBundle\Form\Type\DashboardTranslationsType;
use Ecommerce\Entity\ActivityArea;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Security;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use FrontendBundle\Form\Type\Dashboard\FaqType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ProjectType extends AbstractType
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
                    'company' => [
                        'field_type' => DashboardTextType::class,
                        'label' => 'Компания',
                        'helpBlock' => null,
                        'maxLength' => 255
                    ],
                    'shortDescription' => [
                        'field_type' => DashboardWYSIWYGType::class,
                        'attr' => [
                            'class' => 'tinymce',
                            'data-theme' => 'medium'
                        ],
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
            ->add('publishAt', DashboardDateTimeType::class, [
                'label' => 'Дата выполнения',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd.MM.yyyy HH:mm'
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
            ->add('activityAreas', DashboardSelect2EntityType::class, [
                'required' => false,
                'multiple' => true,
                'label' => 'Направления',
                'class' => ActivityArea::class,
                'choice_label' => 'translate.title',
            ])
            ->add('galleryImages', DashboardCollectionType::class, [
                'prototype_template' => '@Ecommerce/dashboard/project/form/_project_gallery_images_prototype.html.twig',
                'entry_type' => ProjectGalleryImageType::class,
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
            ->add('region', DashboardSelect2EntityType::class, [
                'required' => false,
                'multiple' => false,
                'allow_clear' => true,
                'label' => 'Регион',
                'class' => Region::class,
                'choice_label' => 'translate.title',
            ])
            ->add('relatedProject', DashboardSelect2EntityType::class, [
                'required' => false,
                'multiple' => false,
                'allow_clear' => true,
                'label' => 'Проект (внузу внутренней страницы)',
                'class' => Project::class,
                'choice_label' => 'translate.title',
            ])
            ->add('relatedActivityArea', DashboardSelect2EntityType::class, [
                'required' => false,
                'multiple' => false,
                'allow_clear' => true,
                'label' => 'Направление (внузу внутренней страницы)',
                'class' => ActivityArea::class,
                'choice_label' => 'translate.title',
            ])
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Project::class, 'grantedRoles' => null]);
    }
}