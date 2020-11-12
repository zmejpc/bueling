<?php

namespace BackendBundle\Form\Type\Dashboard;

use BackendBundle\Entity\Info;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use UploadBundle\Form\Type\UploadType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SeoBundle\Form\Type\Dashboard\SeoType;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use DashboardBundle\Form\Type\DashboardWYSIWYGType;
use DashboardBundle\Form\Type\DashboardPositionType;
use DashboardBundle\Form\Type\DashboardTextareaType;
use DashboardBundle\Form\Type\DashboardDateTimeType;
use DashboardBundle\Form\Type\DashboardTranslationsType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class InfoType extends AbstractType
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
                    'posterAlt' => [
                        'field_type' => DashboardTextType::class,
                        'label' => 'ui.poster_alt',
                        'translation_domain' => 'DashboardBundle',
                        'helpBlock' => null,
                        'maxLength' => 255,
                        'required' => false,
                    ],
                    'shortDescription' => [
                        'field_type' => DashboardTextareaType::class,
                        'label' => 'form.short_description',
                        'translation_domain' => 'DashboardBundle',
                        'required' => false,
                    ],
                    'description' => [
                        'field_type' => DashboardWYSIWYGType::class,
                        'required' => false,
                        'label' => 'ui.description',
                        'translation_domain' => 'DashboardBundle',
                    ]
                ],
                'excluded_fields' => [
                    'slug', 'id', 'locale', 'translatable', 'createdAt', 'updatedAt', 'treeTitle'
                ]
            ])
            ->add('seo', SeoType::class)
            ->add('poster', UploadType::class, [
                'file_type' => 'info_poster',
                'extensions' => '.jpg, .gif, .png, .svg',
                'label' => 'ui.image',
                'translation_domain' => 'DashboardBundle',
                'required' => false,
            ])
            ->add('publishAt', DashboardDateTimeType::class, [
                'label' => 'ui.datetime',
                'required' => false,
                'translation_domain' => 'DashboardBundle',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd.MM.yyyy HH:mm'
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
            ->setDefaults(['data_class' => Info::class, 'grantedRoles' => null]);
    }
}
