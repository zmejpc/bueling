<?php

namespace NewsBundle\Form\Type\Dashboard;

use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardTranslationsType;
use NewsBundle\Entity\NewsQuizOption;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\DashboardPositionType;
use Symfony\Component\Form\AbstractType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class NewsQuizOptionType extends AbstractType
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
                        'label' => 'ui.title',
                        'translation_domain' => 'DashboardBundle',
                        'helpBlock' => null,
                        'maxLength' => 255,
//                        'divLg' => 'col-lg-12',
//                        'labelLg' => 'col-lg-12'
                    ],
                ],
                'excluded_fields' => [
                    'slug', 'id', 'locale', 'translatable', 'createdAt', 'updatedAt', 'treeTitle'
                ]
            ])
            ->add('position', DashboardPositionType::class, [
                'label' => 'ui.position',
                'required' => false,
                'translation_domain' => 'DashboardBundle',
                'divLg' => 'col-lg-8',
                'labelLg' => 'col-lg-4'
            ])
            ->add('showOnWebsite', DashboardYesNoType::class, [
                'label' => 'ui.show_on_website',
                'required' => false,
                'translation_domain' => 'DashboardBundle',
                'divLg' => 'col-lg-6',
                'labelLg' => 'col-lg-6'
            ])//        ->add('votes')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => NewsQuizOption::class]);
    }
}