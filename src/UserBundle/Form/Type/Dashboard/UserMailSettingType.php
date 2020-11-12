<?php

namespace UserBundle\Form\Type\Dashboard;

use UserBundle\Entity\UserMailSetting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use DashboardBundle\Form\Type\DashboardFormType;
use Symfony\Component\Form\FormBuilderInterface;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use DashboardBundle\Form\Type\DashboardWYSIWYGType;
use DashboardBundle\Form\Type\DashboardTextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardTranslationsType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class UserMailSettingType extends AbstractType
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
            ->add(
                $builder
                    ->create('translations', DashboardFormType::class, [
                        'inherit_data' => true,
                        'tabName' => 'ui.general_info',
                        'tabIcon' => '',
                    ])
                    ->add('translations', DashboardTranslationsType::class, [
                        'label' => false,
                        'fields' => [
                            'senderName' => [
                                'field_type' => DashboardTextType::class,
                                'helpBlock' => null,
                                'maxLength' => 255,
                                'label' => 'ui.sender_name',
                            ],
                            'managerSubject' => [
                                'field_type' => DashboardTextareaType::class,
                                'helpBlock' => null,
                                'maxLength' => 255,
                                'label' => 'ui.manager_subject',
                            ],
                            'messageSubject' => [
                                'field_type' => DashboardTextareaType::class,
                                'helpBlock' => null,
                                'maxLength' => 255,
                                'label' => 'ui.message_subject',
                            ],
                            'messageBody' => [
                                'field_type' => DashboardTextareaType::class,
                                'label' => 'ui.message_body',
                            ],
                            'successFlashTitle' => [
                                'field_type' => DashboardTextType::class,
                                'helpBlock' => null,
                                'maxLength' => 255,
                                'label' => 'ui.title_for_flash_message',
                            ],
                            'successFlashMessage' => [
                                'field_type' => DashboardTextareaType::class,
                                'label' => 'ui.success_flash_message',
                            ],
                        ]
                    ])
            )
            ->add($builder
                ->create('smtpGroup', DashboardFormType::class, [
                    'inherit_data' => true,
                    'tabName' => 'SMTP',
                    'translation_domain' => null,
                ])
                ->add('smtpHost', DashboardTextType::class, [
                    'label' => 'ui.smtp_host',
                    'helpBlock' => null,
                    'maxLength' => 255,
                ])
                ->add('smtpUsername', DashboardTextType::class, [
                    'label' => 'ui.smtp_username',
                    'helpBlock' => null,
                    'maxLength' => 255,
                ])
                ->add('smtpPassword', DashboardTextType::class, [
                    'label' => 'ui.smtp_password',
                    'helpBlock' => null,
                    'maxLength' => 255,
                ])
                ->add('smtpPort', DashboardTextType::class, [
                    'label' => 'ui.smtp_port',
                    'helpBlock' => null,
                    'maxLength' => 255,
                ])
            )
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));

        if ($this->security->isGranted('ROLE_DEVELOPER')) {
            $builder->get('translations')
                ->add('systemName', DashboardTextType::class, [
                    'helpBlock' => null,
                    'maxLength' => 255,
                    'label' => 'form.system_name'
                ]);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => UserMailSetting::class, 'grantedRoles' => null]);
    }
}
