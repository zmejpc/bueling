<?php

namespace Ecommerce\Form\Type\Dashboard;

use DashboardBundle\Form\Type\DashboardPositionType;
use DashboardBundle\Form\Type\DashboardYesNoType;
use DashboardBundle\Form\Type\DashboardTextType;
use UploadBundle\Form\Type\UploadType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Ecommerce\Entity\Partner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Security;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;

/**
 * @author Design studio origami <https://origami.ua>
 */
class PartnerType extends AbstractType
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id = null;
        if ($builder->getData()) {
            $id = $builder->getData()->getId();
        }

        $builder
            ->add('poster', UploadType::class, [
                'file_type' => 'partner_image',
                'extensions' => '.jpg, .gif, .png, .svg',
                'label' => 'ui.poster',
                'required' => false,
            ])
            ->add('link', DashboardTextType::class, [
                'label' => 'ui.link',
                'helpBlock' => null,
                'maxLength' => 255,
                'required' => false,
            ])
            ->add('position', DashboardPositionType::class, [
                'label' => 'ui.position',
            ])
            ->add('showOnWebsite', DashboardYesNoType::class, [
                'label' => 'ui.show_on_website',
                'translation_domain' => 'DashboardBundle',
                'required' => false,
            ])
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Partner::class, 'grantedRoles' => null]);
    }
}