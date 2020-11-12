<?php

namespace ContactBundle\Form\Type\Dashboard;

use ContactBundle\Entity\ContactPhone;
use DashboardBundle\Form\Type\DashboardPositionType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardTextType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ContactPhoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', DashboardTextType::class, [
                'helpBlock' => null,
                'maxLength' => 255,
                'label' => 'ui.phone_number'
            ])
            ->add('position', DashboardPositionType::class, [
                'label' => 'ui.position',
            ])
            ->add('showOnWebsite', DashboardYesNoType::class, [
                'label' => 'ui.show_on_website',
                'translation_domain' => 'DashboardBundle'
            ])
           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => ContactPhone::class, 'request' => null]);
    }
}