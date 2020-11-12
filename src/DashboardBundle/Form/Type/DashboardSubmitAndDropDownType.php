<?php

namespace DashboardBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\SubmitButtonTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class DashboardSubmitAndDropDownType extends AbstractType implements SubmitButtonTypeInterface
{
    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['clicked'] = $form->isClicked();
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ButtonType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dashboard_submit_and_drop_down';
    }
}
