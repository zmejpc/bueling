<?php

namespace StaticBundle\Form\Type\Dashboard;

use StaticBundle\Entity\StaticContent;
use UploadBundle\Form\Type\UploadType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use DashboardBundle\Form\Type\DashboardFormType;
use Symfony\Component\Form\FormBuilderInterface;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use DashboardBundle\Form\Type\DashboardWYSIWYGType;
use DashboardBundle\Form\Type\DashboardTextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardTranslationsType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class StaticContentType extends AbstractType
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
                        'field_type' => DashboardTextareaType::class,
                        'label' => 'ui.title', 'helpBlock' => null, 'maxLength' => 255
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
                ],
            ])
            ->add('img', UploadType::class, [
                'file_type' => 'static_content_img',
                'extensions' => '.jpg, .gif, .png, .svg',
                'label' => 'ui.image',
                'required' => false,
            ])
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));

        if ($this->security->isGranted('ROLE_DEVELOPER')) {
            $builder
                ->add(
                    $builder
                        ->create('linkNamePage', DashboardFormType::class, [
                            'inherit_data' => true,
                            'useGroupFields' => true,
                            'translation_domain' => null
                        ])
                        ->add('linkName', DashboardTextType::class, [
                            'label' => 'form.static_content.link_name',
                            'helpBlock' => null, 'maxLength' => 255,
                            'divLg' => 'col-lg-6', 'labelLg' => 'col-lg-6',
                            'useFormGroup' => false
                        ])
                        ->add('page', DashboardTextType::class, [
                            'helpBlock' => null, 'maxLength' => 255,
                            'label' => 'form.static_content.page',
                            'divLg' => 'col-lg-6', 'labelLg' => 'col-lg-6',
                            'useFormGroup' => false
                        ])
                );
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => StaticContent::class, 'grantedRoles' => null]);
    }
}
