<?php

namespace NewsBundle\Form\Type\Dashboard;

use UploadBundle\Form\Type\UploadType;
use NewsBundle\Entity\NewsGalleryImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\DashboardPositionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Design studio origami <https://origami.ua>
 */
class NewsGalleryImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('img', UploadType::class, [
                'file_type' => 'news_gallery_image',
                'extensions' => '.jpg, .gif, .png, .svg',
                'div_lg' => 'col-lg-12',
                'label' => false,
                'template' => '@News/dashboard/news/gallery_images/form/_upload.html.twig',
                'required' => false
            ])
            ->add('position', DashboardPositionType::class, [
                'label' => 'ui.position',
                'required' => false,
                'divLg' => 'col-lg-8',
                'labelLg' => 'col-lg-4'
            ])
            ->add('showOnWebsite', DashboardYesNoType::class, [
                'label' => 'ui.show_on_website',
                'required' => false,
                'divLg' => 'col-lg-6',
                'labelLg' => 'col-lg-6'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => NewsGalleryImage::class]);
    }
}