<?php

namespace UserBundle\Form\Type\Dashboard;

use UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use DashboardBundle\Form\Type\DashboardFormType;
use Symfony\Component\Form\FormBuilderInterface;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardEmailType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\DashboardChoiceType;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use BackendBundle\Controller\Dashboard\DashboardConfig;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class UserType extends AbstractType
{
    /**
     * @var Security
     */
    protected $security;

    /**
     * @var null|TranslatorInterface
     */
    protected $translator = null;

    /**
     * UserType constructor.
     * @param Security $security
     * @param TranslatorInterface $translator
     */
    public function __construct(Security $security, TranslatorInterface $translator)
    {
        $this->security = $security;
        $this->translator = $translator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                $builder
                    ->create('generalGroup', DashboardFormType::class, [
                        'inherit_data' => true,
                        'tabName' => 'ui.general_info',
                    ])
                    ->add('name', DashboardTextType::class, [
                        'label' => 'ui.name_first',
                        'maxLength' => 255,
                        'required' => false,
                    ])
                    ->add('surname', DashboardTextType::class, [
                        'label' => 'ui.lastname',
                        'maxLength' => 255,
                        'required' => false,
                    ])
                    ->add('phoneNumber', DashboardTextType::class, [
                        'label' => 'ui.phone',
                        'maxLength' => 255,
                        'required' => false,
                    ])
                    ->add('email', DashboardEmailType::class, [
                        'label' => 'ui.email',
                        'translation_domain' => 'UserMessages',
                        'maxLength' => 255
                    ])
                    ->add('plainPassword', PasswordType::class, [
                        'label' => 'Пароль',
                        'translation_domain' => 'UserMessages',
                    ])
                    ->add(
                        $builder
                            ->create('enabled_locked', DashboardFormType::class, [
                                'inherit_data' => true,
                                'useGroupFields' => true,
                            ])
                            ->add('enabled', DashboardYesNoType::class, [
                                'translation_domain' => 'UserMessages',
                                'required' => false,
                                'label' => 'form.user.enabled',
                                'useFormGroup' => false
                            ])
                            ->add('locked', DashboardYesNoType::class, [
                                'required' => false,
                                'label' => 'ui.locked',
                                'useFormGroup' => false
                            ])
                    )
            )
            ->add(
                $builder
                    ->create('rolesGroup', DashboardFormType::class, [
                        'inherit_data' => true,
                        'tabName' => 'ui.roles',
                    ])
                    ->add('roles', DashboardChoiceType::class, [
                        'label' => false,
                        'required' => false,
                        'multiple' => true,
                        'expanded' => true,
                        'choices' => DashboardConfig::getRoles($this->translator)
                    ])
            )
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => User::class, 'grantedRoles' => null]);
    }
}