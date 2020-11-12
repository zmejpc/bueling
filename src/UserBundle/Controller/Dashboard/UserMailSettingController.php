<?php

namespace UserBundle\Controller\Dashboard;

use Twig\Environment;
use UserBundle\Entity\UserMailSetting;
use Doctrine\ORM\EntityManagerInterface;
use DashboardBundle\Controller\CRUDController;
use Symfony\Contracts\Translation\TranslatorInterface;
use UserBundle\Form\Type\Dashboard\UserMailSettingType;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class UserMailSettingController extends CRUDController
{
    /**
     * @return string
     */
    public function getHeadTitle(): string
    {
        return
            $this->translator->trans('sidebar.configuration.configuration', [], 'DashboardBundle')
            . ' > ' .
            $this->translator->trans('sidebar.customers.users', [], 'UserBundle')
            . ' > ' .
            $this->translator->trans('sidebar.customers.user_settings.user_mail_setting', [], 'UserBundle');
    }

    /**
     * @return array
     */
    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_USER_MAIL_SETTING', 'new' => 'ROLE_DEVELOPER',
            'edit' => 'ROLE_USER_MAIL_SETTING', 'delete' => 'ROLE_DEVELOPER',
        ];
    }

    /**
     * @return array
     */
    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_user_mail_setting_index', 'new' => 'dashboard_user_mail_setting_new',
            'edit' => 'dashboard_user_mail_setting_edit', 'delete' => 'dashboard_user_mail_setting_delete',
        ];
    }

    /**
     * @return array
     */
    public function getPortletHeadIcon(): array
    {
        return [
            'icon' => 'flaticon-multimedia-2',
            'useSvg' => true,
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" id="Combined-Shape" fill="#000000"/>
        <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" id="Combined-Shape" fill="#000000" opacity="0.3"/>
    </g>
</svg>'
        ];
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return UserMailSettingType::class;
    }

    /**
     * @return mixed|UserMailSetting
     */
    public function getFormElement()
    {
        return new UserMailSetting();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        return $this->em->getRepository(UserMailSetting::class);
    }

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'translations-senderName' => $this->translator
                ->trans('ui.sender_name', [], 'DashboardBundle'),
            'translations-managerSubject' => $this->translator
                ->trans('ui.manager_subject', [], 'DashboardBundle'),
            'translations-messageSubject' => $this->translator
                ->trans('ui.message_subject', [], 'DashboardBundle'),
            'translations-successFlashTitle' => $this->translator
                ->trans('ui.title_for_flash_message', [], 'DashboardBundle'),
            'translations-successFlashMessage' => $this->translator
                ->trans('ui.success_flash_message', [], 'DashboardBundle'),
            'systemName' => [
                'locked' => true,
                'title' => $this->translator->trans('form.system_name', [], 'DashboardBundle'),
            ]
        ];
    }

    /**
     * @param $item
     * @return array
     */
    public function createDataForList($item, Environment $twig): array
    {
        $translate = $item->translate();
        return [
            'translations-senderName' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $translate->getSenderName(),
            ]),
            'translations-managerSubject' => $translate->getManagerSubject(),
            'translations-messageSubject' => $translate->getMessageSubject(),
            'translations-successFlashTitle' => $translate->getSuccessFlashTitle(),
            'translations-successFlashMessage' => $translate->getSuccessFlashMessage(),
            'systemName' => $item->getSystemName()
        ];
    }
}