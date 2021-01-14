<?php

namespace BackendBundle\Form\Type\Dashboard;

use DashboardBundle\Form\Type\DashboardTranslationsType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use Symfony\Component\Form\FormBuilderInterface;
use DashboardBundle\Form\Type\DashboardTextType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\AbstractType;
use BackendBundle\Entity\Region;

/**
 * @author Design studio origami <https://origami.ua>
 */
class RegionType extends AbstractType
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
                        'translation_domain' => 'DashboardBundle',
                        'helpBlock' => null,
                        'maxLength' => 255
                    ],
                ],
                'excluded_fields' => [
                    'id', 'locale',
                ]
            ]);

        $builder
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data_class' => Region::class, 'grantedRoles' => null]);
    }
}
