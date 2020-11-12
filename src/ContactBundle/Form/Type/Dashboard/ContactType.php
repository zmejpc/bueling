<?php

namespace ContactBundle\Form\Type\Dashboard;

use DashboardBundle\Form\Type\DashboardEmailType;
use DashboardBundle\Form\Type\DashboardSelect2EntityType;
use DashboardBundle\Form\Type\DashboardTextareaType;
use DashboardBundle\Form\Type\DashboardTextType;
use Doctrine\ORM\EntityRepository;
use ContactBundle\Entity\Contact;
use ContactBundle\Entity\ContactStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use DashboardBundle\Form\Type\AddSaveBtnSubscriber;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ContactType extends AbstractType
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
                'label' => 'ui.first_name'
            ])
            ->add('phone', DashboardTextType::class, [
                'maxLength' => 255,
                'label' => 'ui.phone_number'
            ])
            ->add('email', DashboardEmailType::class, [
                'label' => 'ui.email',
                'helpBlock' => null,
                'maxLength' => 255,
                'required' => false
            ])
            ->add('message', DashboardTextareaType::class, [
                'label' => 'ui.message'
            ])
            ->add('status', DashboardSelect2EntityType::class, [
                'required' => false,
                'label' => 'ui.status',
                'class' => ContactStatus::class,
                'choice_label' => 'translate.title',
                'query_builder' => function (EntityRepository $er) {
                    return $er->getContactStatusesForContactForm();
                },
            ])
            ->addEventSubscriber(new AddSaveBtnSubscriber($this->security));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Contact::class, 'grantedRoles' => null]);
    }
}