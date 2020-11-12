<?php

namespace ContactBundle\Form\Type\Dashboard;

use ContactBundle\Entity\CallbackManager;
use DashboardBundle\Form\Type\DashboardEmailType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\DashboardTextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;

/**
 * @author Design studio origami <https://origami.ua>
 */
class CallbackManagerType extends AbstractType
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', DashboardEmailType::class, [
                'label' => 'ui.email',
                'helpBlock' => null,
                'maxLength' => 255,
            ])
            ->add('name', DashboardTextType::class, [
                'helpBlock' => null,
                'maxLength' => 255,
                'label' => 'ui.first_name'
            ])
            ->add('isSendForEmail', DashboardYesNoType::class, [
                'label' => 'form.is_send_for_email',
                'translation_domain' => 'DashboardBundle'
            ])
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => CallbackManager::class, 'grantedRoles' => null]);
    }
}