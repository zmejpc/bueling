<?php

namespace ContactBundle\Form\Type\Dashboard;

use ContactBundle\Entity\ContactStatus;
use DashboardBundle\Form\Type\DashboardPositionType;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardTranslationsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ContactStatusType extends AbstractType
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
            ->add('position', DashboardPositionType::class, [
                'label' => 'ui.position',
            ])
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => ContactStatus::class, 'grantedRoles' => null]);
    }
}