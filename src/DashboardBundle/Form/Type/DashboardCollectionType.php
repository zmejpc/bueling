<?php

namespace DashboardBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class DashboardCollectionType extends AbstractType
{
    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['prototype_template'] = $options['prototype_template'];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'prototype_template' => null,
            'translation_domain' => 'DashboardBundle',
        ]);
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return CollectionType::class;
    }
}