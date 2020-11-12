<?php

namespace NewsBundle\Form\Type\Dashboard;

use DashboardBundle\Form\Type\DashboardCollectionType;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardTranslationsType;
use NewsBundle\Entity\NewsQuizOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use NewsBundle\Entity\NewsQuiz;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\DashboardPositionType;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;

/**
 * @author Design studio origami <https://origami.ua>
 */
class NewsQuizType extends AbstractType
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
            ->add('translations', DashboardTranslationsType::class, [
                'label' => false,
                'fields' => [
                    'title' => [
                        'field_type' => DashboardTextType::class,
                        'label' => 'ui.title',
                        'translation_domain' => 'DashboardBundle',
                        'helpBlock' => null,
                        'maxLength' => 255
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
//                'divLg' => 'col-lg-12',
//                'labelLg' => 'col-lg-12'
            ])
            ->add('showOnWebsite', DashboardYesNoType::class, [
                'label' => 'ui.show_on_website',
                'required' => false,
                'translation_domain' => 'DashboardBundle',
                'divLg' => 'col-lg-6',
                'labelLg' => 'col-lg-6'
            ])
            ->add('quizOptions', DashboardCollectionType::class, [
                'prototype_template' => '@News/dashboard/news/form/_news_quiz_option_prototype.html.twig',
                'entry_type' => NewsQuizOptionType::class,
                'prototype_data' => new NewsQuizOption(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ]);

        $builder
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => NewsQuiz::class, 'grantedRoles' => null]);
    }
}