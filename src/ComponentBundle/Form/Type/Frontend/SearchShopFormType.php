<?php

namespace ComponentBundle\Form\Type\Frontend;

use Symfony\Component\Form\AbstractType;
use FrontendBundle\Controller\SearchController;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class SearchShopFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class, [
                'attr' => [
                    'placeholder' => 'Я хочу найти...'
                ]
            ])
            ->add('type', SearchShopTypeType::class, [
                'label' => false,
                'choices' => SearchController::getSearchShopChoices(),
                'expanded' => true,
                'empty_data' => SearchController::getSearchShopEmptyData(),
            ]);
    }

    public function getBlockPrefix()
    {
        return $this->getName();
    }

    public function getName()
    {
        return 'search_form';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => null]);
    }
}
