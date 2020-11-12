<?php

namespace NewsBundle\Form\Type\Dashboard;

use DashboardBundle\Form\Type\DashboardFormType;
use NewsBundle\Entity\NewsTag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use DashboardBundle\Form\Type\DashboardPositionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\DashboardTranslationsType;

/**
 * @author Design studio origami <https://origami.ua>
 */
class NewsTagType extends AbstractType
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
            ->add('translations', DashboardTranslationsType::class, [
                'label' => false,
                'fields' => [
                    'title' => [
                        'field_type' => DashboardTextType::class,
                        'label' => 'ui.title',
                        'helpBlock' => null,
                        'maxLength' => 255
                    ]
                ],
                'excluded_fields' => [
                    'slug', 'id', 'locale', 'translatable', 'createdAt', 'updatedAt', 'treeTitle'
                ]
            ])
            ->add(
                $builder
                    ->create('linkNamePage', DashboardFormType::class, [
                        'inherit_data' => true,
                        'useGroupFields' => true,
                        'translation_domain' => null
                    ])
                    ->add('showOnWebsite', DashboardYesNoType::class, [
                        'label' => 'ui.show_on_website',
                        'required' => false,
                        'divLg' => 'col-lg-6',
                        'labelLg' => 'col-lg-6'
                    ])
                    ->add('position', DashboardPositionType::class, [
                        'label' => 'ui.position',
                        'required' => false,
                        'divLg' => 'col-lg-6',
                        'labelLg' => 'col-lg-6'
                    ])
            )
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => NewsTag::class, 'grantedRoles' => null]);
    }
}