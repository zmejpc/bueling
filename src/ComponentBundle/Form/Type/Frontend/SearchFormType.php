<?php

namespace ComponentBundle\Form\Type\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class SearchFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class, [
                'translation_domain' => 'DashboardBundle',
                'attr' => ['placeholder' => 'ui.search']
            ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'search_form';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => null]);
    }
}
