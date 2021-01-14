<?php

namespace BackendBundle\Form\Type\Dashboard;

use DashboardBundle\Form\Type\DashboardTranslationsType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardPositionType;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\DashboardTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\AbstractType;
use UploadBundle\Form\Type\UploadType;
use BackendBundle\Entity\Document;

/**
 * @author Design studio origami <https://origami.ua>
 */
class DocumentType extends AbstractType
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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', DashboardTranslationsType::class, [
                'label' => false,
                'fields' => [
                    'title' => [
                        'label' => 'Название',
                        'field_type' => DashboardTextType::class,
                        'helpBlock' => null,
                        'maxLength' => 255,
                        'disabled' => false
                    ],
                ]
            ])
            ->add('position', DashboardPositionType::class, [
                'label' => 'ui.position',
            ])
            ->add('showOnWebsite', DashboardYesNoType::class, [
                'label' => 'ui.show_on_website',
                'translation_domain' => 'DashboardBundle',
            ])
            ->add('document', UploadType::class, [
                'file_type' => 'documents',
                'extensions' => '.pdf, .doc, .docx, .txt',
                'label' => 'Файл',
                'required' => false,
            ]);

        $builder
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Document::class, 'grantedRoles' => null]);
    }
}