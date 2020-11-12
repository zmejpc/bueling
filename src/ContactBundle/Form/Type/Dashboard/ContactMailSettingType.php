<?php

namespace ContactBundle\Form\Type\Dashboard;

use DashboardBundle\Form\Type\DashboardWYSIWYGType;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardTranslationsType;
use ContactBundle\Entity\ContactMailSetting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ContactMailSettingType extends AbstractType
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', DashboardTranslationsType::class, [
                'label' => false,
                'fields' => [
                    'senderName' => [
                        'field_type' => DashboardTextType::class,
                        'helpBlock' => null,
                        'maxLength' => 255,
                        'label' => 'ui.sender_name',
                        'translation_domain' => 'DashboardBundle',
                    ],
                    'managerSubject' => [
                        'field_type' => DashboardTextType::class,
                        'helpBlock' => null,
                        'maxLength' => 255,
                        'label' => 'ui.manager_subject',
                        'translation_domain' => 'DashboardBundle',
                    ],
                    'messageSubject' => [
                        'field_type' => DashboardTextType::class,
                        'helpBlock' => null,
                        'maxLength' => 255,
                        'label' => 'ui.message_subject',
                        'translation_domain' => 'DashboardBundle',
                    ],
                    'messageBody' => [
                        'field_type' => DashboardWYSIWYGType::class,
                        'label' => 'ui.message_body',
                        'attr' => [
                            'class' => 'tinymce',
                            'data-theme' => 'medium'
                        ],
                        'translation_domain' => 'DashboardBundle',
                    ],
                    'successFlashTitle' => [
                        'field_type' => DashboardTextType::class,
                        'helpBlock' => null,
                        'maxLength' => 255,
                        'label' => 'ui.title_for_flash_message',
                        'translation_domain' => 'DashboardBundle',
                    ],
                    'successFlashMessage' => [
                        'field_type' => DashboardWYSIWYGType::class,
                        'label' => 'ui.success_flash_message',
                        'attr' => [
                            'class' => 'tinymce',
                            'data-theme' => 'medium'
                        ],
                        'translation_domain' => 'DashboardBundle',
                    ],
                ],
                'excluded_fields' => ['createdAt', 'updatedAt']
            ])
            ->add('smtpHost', DashboardTextType::class, [
                'label' => 'ui.smtp_host',
                'helpBlock' => null,
                'maxLength' => 255,
                'translation_domain' => 'DashboardBundle',
            ])
            ->add('smtpUsername', DashboardTextType::class, [
                'label' => 'ui.smtp_username',
                'helpBlock' => null,
                'maxLength' => 255,
                'translation_domain' => 'DashboardBundle',
            ])
            ->add('smtpPassword', DashboardTextType::class, [
                'label' => 'ui.smtp_password',
                'helpBlock' => null,
                'maxLength' => 255,
                'translation_domain' => 'DashboardBundle',
            ])
            ->add('smtpPort', DashboardTextType::class, [
                'label' => 'ui.smtp_port',
                'helpBlock' => null,
                'maxLength' => 255,
                'translation_domain' => 'DashboardBundle',
            ])
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => ContactMailSetting::class, 'grantedRoles' => null]);
    }
}