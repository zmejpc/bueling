<?php

namespace UserBundle\Form\Type\Frontend;

use UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Ecommerce\Form\Type\Frontend\UserShippingMethodType;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'form.name',
                'translation_domain' => 'UserBundle',
            ])
            ->add('surname', TextType::class, [
                'label' => 'form.surname',
                'translation_domain' => 'UserBundle',
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'form.phone',
                'translation_domain' => 'UserBundle',
            ])
            ->add('shippingMethod', UserShippingMethodType::class, [
                'label' => 'form.phone',
                'mapped' => false
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.email',
                'translation_domain' => 'UserBundle',
                'attr' => [
                    'class' => 'form.user.email',
                    'placeholder' => 'validators.user.email.not_blank',
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'translation_domain' => 'UserBundle',
                'attr' => [
                    'class' => 'form.user.password.label',
                    'placeholder' => 'ui.password',

                ],
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'form.password',
                    'attr' => [
                        'placeholder' => 'validators.user.plainPassword.not_blank',
                    ]
                ],
                'second_options' => [
                    'label' => 'form.repeat_password',
                    'attr' => [
                        'placeholder' => 'ui.repeat_password',
                    ]
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
