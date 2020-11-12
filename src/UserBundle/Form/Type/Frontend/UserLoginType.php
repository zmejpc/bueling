<?php

namespace UserBundle\Form\Type\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class UserLoginType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('_username', TextType::class, [
                'label' => 'form.email',
                'translation_domain' => 'UserBundle',
                'attr' => [
                    'placeholder' => 'ui.email',
                ]
            ])
            ->add('_password', PasswordType::class, [
                'label' => 'form.password',
                'attr' => [
                    'placeholder' => 'form.login.password',
                ],
                'translation_domain' => 'UserBundle',
            ])
            ->add('_remember_me', CheckboxType::class, [
                'label' => 'form.login.remember_me',
                'translation_domain' => 'UserBundle',
                'required' => false,
            ]);

        if(!empty($options['_target_path'])) {
            $builder->add('_target_path', HiddenType::class, [
                'data' => $options['_target_path'],
            ]);
        }
    }

    /**
     * @return string|null
     */
    public function getBlockPrefix()
    {
        return null;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            '_target_path' => null,
        ]);
    }
}
