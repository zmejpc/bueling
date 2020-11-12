<?php

namespace ContactBundle\Form\Type\Frontend;

use ContactBundle\Entity\Callback;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

/**
 * @author Design studio origami <https://origami.ua>
 */
class CallbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'ui.first_name',
                'translation_domain' => 'DashboardBundle',
                'required' => false
            ])
            ->add('phone', TelType::class, [
                'label' => 'form.phone_number',
                'translation_domain' => 'DashboardBundle',
                'data' => '+380',
                'attr' => ['type' => 'tel'],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'ui.message.message',
                'translation_domain' => 'DashboardBundle',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Callback::class, 'request' => null]);
    }
}