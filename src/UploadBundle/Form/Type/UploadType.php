<?php

namespace UploadBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class UploadType extends AbstractType
{
    /**
     * @var
     */
    private $secureToken;

    /**
     * UploadType constructor.
     * @param $csrfProvider
     * @param SessionInterface $session
     */
    public function __construct($csrfProvider, SessionInterface $session)
    {
        $this->secureToken = $csrfProvider->getToken('')->getValue();
        $session->set('secure_token', $this->secureToken);
    }


    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['file_type'] = $options['file_type'];
        $view->vars['template'] = $options['template'];
        $view->vars['extensions'] = $options['extensions'];
        $view->vars['multi_selection'] = $options['multi_selection'];
        $view->vars['secure_token'] = $this->secureToken;
//        $view->vars['crop'] = $options['crop'];
//        $view->vars['crop_width'] = $options['crop_width'];
//        $view->vars['crop_height'] = $options['crop_height'];
        $view->vars['div_lg'] = $options['div_lg'];
        $view->vars['label_lg'] = $options['label_lg'];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'compound' => false,
            'multi_selection' => false,
            'class' => 'upload',
            'file_type' => 'default',
            'template' => '@Upload/default/crud/form/_upload.html.twig',
            'extensions' => '',
//            'crop' => false,
//            'crop_width' => null,
//            'crop_height' => null,
            'div_lg' => 'col-lg-10', 'label_lg' => 'col-lg-2',
            'translation_domain' => 'DashboardBundle',
        ]);
    }

    /**
     * @return null|string
     */
    public function getParent()
    {
        return FormType::class;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'upload_type';
    }
}
