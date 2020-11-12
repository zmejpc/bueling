<?php

namespace DashboardBundle\Form\Type;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class AddSaveBtnSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * StaticContentType constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData'
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        $grantedRoles = $form->getConfig()->getOption('grantedRoles');


        if (!$data || null === $data->getId()) {
            if ($this->security->isGranted($grantedRoles['edit'])) {
                $form->add('createAndEdit', DashboardSubmitType::class, [
                    'label' => 'ui.create.create',
                    'translation_domain' => 'DashboardBundle',
                ]);
            }
            if ($this->security->isGranted($grantedRoles['new'])) {
                $form->add('createAndCreate', DashboardSubmitAndDropDownType::class, [
                    'label' => 'ui.create.create_and_add_new',
                    'translation_domain' => 'DashboardBundle',
                    'attr' => [
                        'data-i-icon' => 'flaticon-add'
                    ]
                ]);
            }
            if ($this->security->isGranted($grantedRoles['index'])) {
                $form->add('createAndList', DashboardSubmitAndDropDownType::class, [
                    'label' => 'ui.create.create_and_return_to_the_list',
                    'translation_domain' => 'DashboardBundle',
                    'attr' => [
                        'data-svg-icon' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <rect id="bound" x="0" y="0" width="24" height="24"></rect>
                <path d="M21.4451171,17.7910156 C21.4451171,16.9707031 21.6208984,13.7333984 19.0671874,11.1650391 C17.3484374,9.43652344 14.7761718,9.13671875 11.6999999,9 L11.6999999,4.69307548 C11.6999999,4.27886191 11.3642135,3.94307548 10.9499999,3.94307548 C10.7636897,3.94307548 10.584049,4.01242035 10.4460626,4.13760526 L3.30599678,10.6152626 C2.99921905,10.8935795 2.976147,11.3678924 3.2544639,11.6746702 C3.26907199,11.6907721 3.28437331,11.7062312 3.30032452,11.7210037 L10.4403903,18.333467 C10.7442966,18.6149166 11.2188212,18.596712 11.5002708,18.2928057 C11.628669,18.1541628 11.6999999,17.9721616 11.6999999,17.7831961 L11.6999999,13.5 C13.6531249,13.5537109 15.0443703,13.6779456 16.3083984,14.0800781 C18.1284272,14.6590944 19.5349747,16.3018455 20.5280411,19.0083314 L20.5280247,19.0083374 C20.6363903,19.3036749 20.9175496,19.5 21.2321404,19.5 L21.4499999,19.5 C21.4499999,19.0068359 21.4451171,18.2255859 21.4451171,17.7910156 Z" id="Shape" fill="#000000" fill-rule="nonzero"></path>
            </g>
        </svg>'
                    ]
                ]);
            }
        } else {
            if ($this->security->isGranted($grantedRoles['edit'])) {
                $form->add('saveAndEdit', DashboardSubmitType::class, [
                    'label' => 'ui.save.save',
                    'translation_domain' => 'DashboardBundle',
                ]);
            }
            if ($this->security->isGranted($grantedRoles['new'])) {
                $form->add('saveAndCreate', DashboardSubmitAndDropDownType::class, [
                    'label' => 'ui.save.save_and_add_new',
                    'translation_domain' => 'DashboardBundle',
                    'attr' => [
                        'data-i-icon' => 'la la-plus'
                    ]
                ]);
            }
            if ($this->security->isGranted($grantedRoles['index'])) {
                $form->add('saveAndList', DashboardSubmitAndDropDownType::class, [
                    'label' => 'ui.save.save_and_return_to_the_list',
                    'translation_domain' => 'DashboardBundle',
                    'attr' => [
                        'data-i-icon' => 'la la-undo'
                    ]
                ]);
            }
        }
    }
}