<?php

namespace SeoBundle\Form\Type\Dashboard;

use SeoBundle\Entity\SeoPage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use DashboardBundle\Form\Type\DashboardTextType;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Design studio origami <https://origami.ua>
 */
class SeoPageType extends AbstractType
{
    /**
     * @var Security
     */
    protected $security;

    /**
     * SeoPageType constructor.
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
        $builder->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
        $builder->add('seo', SeoRequiredType::class);

        if ($this->security->isGranted('ROLE_DEVELOPER')) {
            $builder
                ->add('systemName', DashboardTextType::class, [
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
        $resolver->setDefaults(['data_class' => SeoPage::class, 'grantedRoles' => null]);
    }
}