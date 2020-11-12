<?php

namespace StaticBundle\Form\Type\Dashboard;

use StaticBundle\Entity\FooterImage;
use UploadBundle\Form\Type\UploadType;
use DashboardBundle\Form\Type\DashboardTextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardPositionType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class FooterImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('img', UploadType::class, [
                'file_type' => 'footer_settings_img',
                'extensions' => '.jpg, .gif, .png, .svg',
                'label' => 'ui.image',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => FooterImage::class, 'grantedRoles' => null]);
    }
}