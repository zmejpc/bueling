<?php

namespace SeoBundle\Form\Type\Dashboard;

use SeoBundle\Entity\Seo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardTextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardTranslationsType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class SeoRequiredType extends AbstractType
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
                    'metaTitle' => [
                        'field_type' => DashboardTextareaType::class,
                        'label' => 'form.seo.meta_title',
                        'translation_domain' => 'SeoBundle',
                        'maxLength' => 255,
                        'required' => true,
                    ],
                    'h1' => [
                        'field_type' => DashboardTextareaType::class,
                        'label' => 'form.seo.h1',
                        'translation_domain' => 'SeoBundle',
                        'maxLength' => 255,
                        'required' => true,
                    ],
                    'breadcrumb' => [
                        'field_type' => DashboardTextareaType::class,
                        'label' => 'form.seo.bread_crumbs',
                        'translation_domain' => 'SeoBundle',
                        'maxLength' => 255,
                        'required' => true,
                    ],
                    'metaKeyword' => [
                        'field_type' => DashboardTextareaType::class,
                        'label' => 'form.seo.meta_keyword',
                        'translation_domain' => 'SeoBundle',
                        'required' => false,
                    ],
                    'metaDescription' => [
                        'field_type' => DashboardTextareaType::class,
                        'label' => 'form.seo.meta_description',
                        'translation_domain' => 'SeoBundle',
                        'required' => false,
                    ]
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Seo::class]);
    }
}
