<?php

namespace BackendBundle\Form\Type\Dashboard;

use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardTranslationsType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\DashboardPositionType;
use DashboardBundle\Form\Type\DashboardWYSIWYGType;
use BackendBundle\Entity\SliderImage;
use UploadBundle\Form\Type\UploadType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use Symfony\Component\Security\Core\Security;

/**
 * @author Design studio origami <https://origami.ua>
 */
class SliderImageType extends AbstractType
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('translations', DashboardTranslationsType::class, [
            'label' => false,
            'fields' => [
                'title' => [
                    'field_type' => DashboardTextType::class,
                    'label' => 'ui.title',
                    'required' => false
                ],
                'description' => [
                    'field_type' => DashboardWYSIWYGType::class,
                    'label' => 'ui.description',
                ]
            ],
            'excluded_fields' => ['createdAt', 'updatedAt']
        ])
        ->add('link', DashboardTextType::class, [
            'label' => 'ui.link',
            'required' => false,
        ])
        ->add('position', DashboardPositionType::class, [
            'label' => 'ui.position',
            'translation_domain' => 'DashboardBundle',
            'required' => false
        ])
        ->add('showOnWebsite', DashboardYesNoType::class, [
            'label' => 'ui.show_on_website',
            'translation_domain' => 'DashboardBundle',
            'required' => false
        ])
        ->add('image', UploadType::class, [
            'file_type' => 'slider_image',
            'extensions' => '.jpg, .gif, .png, .svg',
            'label' => 'ui.poster',
            'required' => false,
        ])
        ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => SliderImage::class, 'grantedRoles' => null]);
    }
}