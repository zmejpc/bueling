<?php

namespace DashboardBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Design studio origami <https://origami.ua>
 */
class DashboardWYSIWYGType extends AbstractType
{
    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr'] = $options['attr'];
        $view->vars['labelLg'] = $options['labelLg'];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'divLg' => 'col-lg-10', 'labelLg' => 'col-lg-2',
            'translation_domain' => 'DashboardBundle',
        ]);
    }

    /**
     * @return null|string
     */
    public function getParent()
    {
        return CKEditorType::class;
    }
}
