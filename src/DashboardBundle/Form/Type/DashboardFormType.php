<?php

namespace DashboardBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class DashboardFormType extends AbstractType
{
    /**
     * @return null|string
     */
    public function getParent()
    {
        return FormType::class;
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr'] = $options['attr'];
        $view->vars['tabName'] = $options['tabName'];
        $view->vars['tabIcon'] = $options['tabIcon'];
        $view->vars['useGroupFields'] = $options['useGroupFields'];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'tabName' => null, 'tabIcon' => null, 'translation_domain' => 'DashboardBundle',
            'useGroupFields' => false
        ]);
    }
}
