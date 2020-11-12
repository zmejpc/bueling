<?php

namespace Ecommerce\Form\Type\Dashboard;

use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardTranslationsType;
use Ecommerce\Entity\ProductStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use Symfony\Component\Security\Core\Security;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ProductStatusType extends AbstractType
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('translations', DashboardTranslationsType::class, [
            'label' => false,
            'fields' => [
                'title' => [
                    'field_type' => DashboardTextType::class,
                    'helpBlock' => null,
                    'maxLength' => 255,
                    'label' => 'ui.title',
                ]
            ],
            'excluded_fields' => ['createdAt', 'updatedAt']
        ])
        ->add('systemName', DashboardTextType::class, [
            'helpBlock' => null,
            'maxLength' => 255,
            'label' => 'form.system_name'
        ])
        ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => ProductStatus::class, 'grantedRoles' => null]);
    }
}