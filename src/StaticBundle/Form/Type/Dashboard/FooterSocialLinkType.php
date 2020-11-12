<?php

namespace StaticBundle\Form\Type\Dashboard;

use StaticBundle\Entity\FooterSocialLink;
use UploadBundle\Form\Type\UploadType;
use DashboardBundle\Form\Type\DashboardTextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardPositionType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class FooterSocialLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', DashboardTextType::class, [
                'label' => 'Название',
            ])
            ->add('link', DashboardTextType::class, [
                'label' => 'Ссылка',
            ])
            ->add('position', DashboardPositionType::class, [
                'label' => 'ui.priority',
            ])
            ->add('img', UploadType::class, [
                'file_type' => 'footer_social_link_img',
                'extensions' => '.jpg, .gif, .png, .svg',
                'label' => 'ui.image',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => FooterSocialLink::class, 'grantedRoles' => null]);
    }
}