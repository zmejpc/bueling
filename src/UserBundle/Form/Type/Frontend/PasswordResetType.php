<?php

namespace UserBundle\Form\Type\Frontend;

use UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class PasswordResetType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'translation_domain' => 'UserBundle',
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'form.password',
                    'translation_domain' => 'UserBundle',
                ],
                'second_options' => [
                    'label' => 'form.repeat_password',
                    'translation_domain' => 'UserBundle'
                ],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }
}
