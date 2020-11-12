<?php

namespace NewsBundle\Form\Type\Dashboard;

use NewsBundle\Entity\NewsComment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardFormType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use DashboardBundle\Form\Type\DashboardTextareaType;
use DashboardBundle\Form\Type\DashboardSubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsCommentType extends AbstractType
{
    /**
     * @var Security
     */
    protected $security;

    /**
     * StaticContentType constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
     public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', DashboardTextType::class, [
                'label' => 'ui.show_on_website',
                'label' => 'ui.title',
                'helpBlock' => null,
                'maxLength' => 255
            ])
            ->add('content', DashboardTextareaType::class, [
                'label' => 'ui.show_on_website',
                'label' => 'form.short_description',
                'helpBlock' => null
            ])
            ->add('showOnWebsite', DashboardYesNoType::class, [
                'label' => 'ui.show_on_website',
                'required' => false,
                'useFormGroup' => false,
                'divLg' => 'col-lg-6',
                'labelLg' => 'col-lg-6'
            ])
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => NewsComment::class, 'grantedRoles' => null]);
    }
}