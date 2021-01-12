<?php

namespace ContactBundle\Form\Type\Frontend;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use ContactBundle\Entity\Contact;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => null,
                'translation_domain' => 'FrontendBundle',
                'required' => false
            ])
            ->add('phone', TelType::class, [
                'label' => null,
                'translation_domain' => 'FrontendBundle',
            ])
            ->add('email', EmailType::class, [
                'label' => null,
                'translation_domain' => 'FrontendBundle',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Contact::class, 'request' => null]);
    }
}