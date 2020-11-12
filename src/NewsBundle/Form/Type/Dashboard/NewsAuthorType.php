<?php

namespace NewsBundle\Form\Type\Dashboard;

use NewsBundle\Entity\NewsAuthor;
use UploadBundle\Form\Type\UploadType;
use Symfony\Component\Form\AbstractType;
use SeoBundle\Form\Type\Dashboard\SeoType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardFormType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use SeoBundle\Form\Type\Dashboard\AddSeoSubscriber;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use DashboardBundle\Form\Type\DashboardPositionType;
use DashboardBundle\Form\Type\DashboardTextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardTranslationsType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class NewsAuthorType extends AbstractType
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
                                'maxLength' => 255
                            ],
                            'shortDescription' => [
                                'field_type' => DashboardTextareaType::class,
                                'label' => 'form.short_description',
                                'required' => false,
                            ],
                        ],
                        'excluded_fields' => [
                            'slug', 'id', 'locale', 'translatable', 'createdAt', 'updatedAt', 'treeTitle'
                        ]
                    ])
                    ->add('poster', UploadType::class, [
                        'file_type' => 'news_author_poster',
                        'extensions' => '.jpg, .gif, .png, .svg',
                        'label' => 'ui.image',
                        'required' => false,
                    ])
                    ->add(
                        $builder->create('positionShowOnWebsite', DashboardFormType::class, [
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
            ->addEventSubscriber(new AddSeoSubscriber())
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => NewsAuthor::class, 'grantedRoles' => null]);
    }
}