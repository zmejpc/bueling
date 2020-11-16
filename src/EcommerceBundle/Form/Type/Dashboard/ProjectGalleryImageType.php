<?php

namespace Ecommerce\Form\Type\Dashboard;

use UploadBundle\Form\Type\UploadType;
use DashboardBundle\Form\Type\DashboardInteger2Type;
use DashboardBundle\Form\Type\DashboardIntegerType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardTranslationsType;
use Ecommerce\Entity\ProjectGalleryImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ProjectGalleryImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', UploadType::class, [
                'file_type' => 'project_gallery_image',
                'extensions' => '.jpg, .gif, .png, .svg',
                'label' => 'ui.poster',
                'required' => false,
            ])
            ->add('showOnWebsite', DashboardYesNoType::class, [
                'label' => 'ui.show_on_website',
                'translation_domain' => 'DashboardBundle',
                'required' => false
            ])
            ->add('position', DashboardIntegerType::class, [
                'label' => 'ui.position',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => ProjectGalleryImage::class, 'grantedRoles' => null]);
    }
}