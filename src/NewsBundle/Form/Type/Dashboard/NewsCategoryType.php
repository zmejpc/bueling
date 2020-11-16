<?php

namespace NewsBundle\Form\Type\Dashboard;

use NewsBundle\Entity\NewsCategory;
use Symfony\Component\Form\AbstractType;
use SeoBundle\Form\Type\Dashboard\SeoType;
use Symfony\Component\Security\Core\Security;
use DashboardBundle\Form\Type\DashboardFormType;
use Symfony\Component\Form\FormBuilderInterface;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use SeoBundle\Form\Type\Dashboard\AddSeoSubscriber;
use DashboardBundle\Form\Type\DashboardWYSIWYGType;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use DashboardBundle\Form\Type\DashboardPositionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardTranslationsType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class NewsCategoryType extends AbstractType
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
                $builder
                    ->create('translations', DashboardFormType::class, [
                        'inherit_data' => true,
                        'tabName' => 'ui.general_info',
                    ])
                    ->add('translations', DashboardTranslationsType::class, [
                        'label' => false,
                        'fields' => [
                            'title' => [
                                'field_type' => DashboardTextType::class,
                                'label' => 'ui.title',
                                'helpBlock' => null,
                                'maxLength' => 255,
                            ],
                            // 'shortDescription' => [
                            //     'field_type' => DashboardWYSIWYGType::class,
                            //     'label' => 'form.short_description',
                            //     'required' => false,
                            // ],
                            // 'description' => [
                            //     'field_type' => DashboardWYSIWYGType::class,
                            //     'required' => false,
                            //     'label' => 'ui.description',
                            // ]
                        ],
                        'excluded_fields' => [
                            'slug', 'id', 'locale', 'translatable', 'createdAt', 'updatedAt', 'treeTitle', 'description', 'shortDescription'
                        ]
                    ])
                    ->add(
                        $builder->create('positionShowOnWebsiteShowInMenu', DashboardFormType::class, [
                            'inherit_data' => true,
                            'useGroupFields' => true,
                            'translation_domain' => null
                        ])
                            ->add('showOnWebsite', DashboardYesNoType::class, [
                                'label' => 'ui.show_on_website',
                                'required' => false,
                                'divLg' => 'col-lg-4',
                                'useFormGroup' => false,
                                'labelLg' => 'col-lg-8'
                            ])
                            // ->add('showInMenu', DashboardYesNoType::class, [
                            //     'label' => 'ui.show_in_menu',
                            //     'required' => false,
                            //     'divLg' => 'col-lg-4',
                            //     'useFormGroup' => false,
                            //     'labelLg' => 'col-lg-8'
                            // ])
                            // ->add('showOnMainPage', DashboardYesNoType::class, [
                            //     'label' => 'ui.show_on_main_page',
                            //     'required' => false,
                            //     'divLg' => 'col-lg-4',
                            //     'useFormGroup' => false,
                            //     'labelLg' => 'col-lg-8'
                            // ])
                            ->add('position', DashboardPositionType::class, [
                                'label' => 'ui.position',
                                'divLg' => 'col-lg-8',
                                'labelLg' => 'col-lg-4',
                                'useFormGroup' => false,
                                'required' => false
                            ])
                    )
            )
            ->addEventSubscriber(new AddSeoSubscriber())
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => NewsCategory::class, 'grantedRoles' => null]);
    }
}