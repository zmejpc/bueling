<?php

namespace ContactBundle\Form\Type\Dashboard;

use DashboardBundle\Form\Type\DashboardSelect2EntityType;
use DashboardBundle\Form\Type\DashboardTextType;
use Doctrine\ORM\EntityRepository;
use ContactBundle\Entity\Callback;
use ContactBundle\Entity\CallbackStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardTextareaType;
use Symfony\Component\Security\Core\Security;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;

/**
 * @author Design studio origami <https://origami.ua>
 */
class CallbackType extends AbstractType
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', DashboardTextType::class, [
                'maxLength' => 255,
                'label' => 'ui.first_name',
                'required' => false
            ])
            ->add('phone', DashboardTextType::class, [
                'maxLength' => 255,
                'label' => 'ui.phone_number'
            ])
            ->add('status', DashboardSelect2EntityType::class, [
                'required' => false,
                'label' => 'ui.status',
                'class' => CallbackStatus::class,
                'choice_label' => 'translate.title',
                'query_builder' => function (EntityRepository $er) {
                    return $er->getCallbackStatusesForCallbackForm();
                },
            ])
            ->add('message', DashboardTextareaType::class, [
                'label' => 'ui.message'
            ])
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Callback::class, 'grantedRoles' => null]);
    }
}