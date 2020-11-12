<?php

namespace BackendBundle\Form\Type\Dashboard;

use BackendBundle\Entity\Slider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use DashboardBundle\Form\Type\DashboardPositionType;
use DashboardBundle\Form\Type\DashboardCollectionType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class SliderType extends AbstractType
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
            ->add('title', DashboardTextType::class, [
                'label' => 'ui.title',
                'required' => false,
                'translation_domain' => 'DashboardBundle',
                'divLg' => 'col-lg-8',
                'labelLg' => 'col-lg-4'
            ])
            ->add('systemName', DashboardTextType::class, [
                'label' => 'form.system_name',
                'translation_domain' => 'DashboardBundle',
                'divLg' => 'col-lg-8',
                'labelLg' => 'col-lg-4'
            ])
            ->add('sliderImages', DashboardCollectionType::class, [
                'prototype_template' => '@Backend/dashboard/slider/form/_slider_images_prototype.html.twig',
                'entry_type' => SliderImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
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
            ->setDefaults(['data_class' => Slider::class, 'grantedRoles' => null]);
    }
}
