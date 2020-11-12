<?php

namespace ContactBundle\Form\Type\Dashboard;

use DashboardBundle\Form\Type\DashboardPositionType;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardTranslationsType;
use ContactBundle\Entity\CallbackStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Security;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Design studio origami <https://origami.ua>
 */
class CallbackStatusType extends AbstractType
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
            ->add('position', DashboardPositionType::class, [
                'label' => 'ui.position',
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
        $resolver->setDefaults(['data_class' => CallbackStatus::class, 'grantedRoles' => null]);
    }
}