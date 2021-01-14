<?php

namespace ContactBundle\Form\Type\Dashboard;

use ContactBundle\Entity\ContactPhoneType;
use DashboardBundle\Form\Type\DashboardPositionType;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardTranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\DashboardCollectionType;
use Symfony\Component\Security\Core\Security;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ContactPhoneTypeType extends AbstractType
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
                    'helpBlock' => null,
                    'maxLength' => 255,
                    'label' => 'ui.title',
                ]
            ],
            'excluded_fields' => ['createdAt', 'updatedAt']
        ])
        ->add('contactPhones', DashboardCollectionType::class, [
            'prototype_template' => '@Contact/dashboard/contact/form/_contact_phone.html.twig',
            'label' => false,
            'entry_type' => \ContactBundle\Form\Type\Dashboard\ContactPhoneType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'by_reference' => false,
        ])
        ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => ContactPhoneType::class, 'grantedRoles' => null]);
    }
}