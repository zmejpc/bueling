<?php

namespace StaticBundle\Form\Type\Dashboard;

use StaticBundle\Entity\FooterSettings;
use DashboardBundle\Form\Type\DashboardCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use Symfony\Component\Security\Core\Security;

/**
 * @author Design studio origami <https://origami.ua>
 */
class FooterSettingsType extends AbstractType
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('socialLinks', DashboardCollectionType::class, [
                'prototype_template' => '@Static/dashboard/footer_settings/form/_social_links_prototype.html.twig',
                'entry_type' => FooterSocialLinkType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ])
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => FooterSettings::class, 'grantedRoles' => null]);
    }
}