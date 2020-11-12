<?php

namespace ContactBundle\Form\Type\Frontend;

use Doctrine\ORM\EntityRepository;
use ContactBundle\Entity\Contact;
use Ecommerce\Entity\Product;
use ContactBundle\Entity\ContactStatus;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ComponentBundle\Form\DataTransformer\IdToEntityTransformer;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'ui.first_name',
                'translation_domain' => 'DashboardBundle',
                'required' => false
            ])
            ->add('message', TextareaType::class, [
                'label' => 'ui.message.message',
                'translation_domain' => 'DashboardBundle',
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'label' => 'ui.email',
                'translation_domain' => 'DashboardBundle',
                'required' => false,
            ])
            ->add('phone', TelType::class, [
                'label' => 'form.phone_number',
                'translation_domain' => 'DashboardBundle',
                'data' => '+380',
            ]);

            if(isset($options['product'])) {
                $builder
                    ->add('product', HiddenType::class, [
                        'data' => !empty($options['product']) ? $options['product'] : null,
                        'data_class' => null,
                    ])
                    ->get('product')->addModelTransformer(new IdToEntityTransformer($options['repository']));
            }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Contact::class, 'request' => null, 'product' => null, 'repository' => null]);
    }
}