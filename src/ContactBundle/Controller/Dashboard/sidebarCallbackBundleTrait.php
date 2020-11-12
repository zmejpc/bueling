<?php

namespace ContactBundle\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use ContactBundle\Entity\Callback;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait sidebarCallbackBundleTrait
{
    private function sidebarCallbackBundle()
    {
        $countNewCallbackRequests = $this->em->getRepository(Callback::class)->countNewCallbackRequests();

        return self::itemSidebar(
            ['ROLE_CALLBACK', 'ROLE_CALLBACK_STATUS_CREATE_EDIT', 'ROLE_CALLBACK_MANAGER_CREATE_EDIT'],
            ['callback/status/edit/', 'callback/manager/edit/', 'callback/edit/'],
            [
                'dashboard_callback_index', 'dashboard_callback_status_index', 'dashboard_callback_status_new',
                'dashboard_callback_manager_index', 'dashboard_callback_manager_new', 'dashboard_callback_mail_setting_edit'
            ], 'icon-call-out', true, $countNewCallbackRequests, 'badge-primary',
            'sidebar.callback.callback', [
            self::itemSidebar(
                ['ROLE_CALLBACK'], ['callback/edit/'], ['dashboard_callback_index'], 'icon-call-in',
                true, $countNewCallbackRequests, 'badge-primary', 'sidebar.callback.callback_form',
                [], 'dashboard_callback_index'
            ),
            self::itemSidebar(
                ['ROLE_CALLBACK_STATUS_CREATE_EDIT', 'ROLE_CALLBACK_MANAGER_CREATE_EDIT', 'ROLE_CALLBACK_MAIL_SETTING_EDIT'],
                ['callback/status/edit/', 'callback/manager/edit/'],
                [
                    'dashboard_callback_status_index', 'dashboard_callback_status_new', 'dashboard_callback_manager_index',
                    'dashboard_callback_manager_new', 'dashboard_callback_mail_setting_edit'
                ],
                'icon-settings', false, null, null,
                'sidebar.callback.callback_settings.callback_settings',
                [
                    self::itemSidebar(
                        ['ROLE_CALLBACK_STATUS_CREATE_EDIT'], ['callback/status/edit/'],
                        ['dashboard_callback_status_index', 'dashboard_callback_status_new'], 'icon-flag',
                        false, null, null, 'sidebar.callback.callback_settings.callback_statuses',
                        [], 'dashboard_callback_status_index'
                    ),
                    self::itemSidebar(
                        ['ROLE_CALLBACK_MANAGER_CREATE_EDIT'], ['callback/manager/edit/'],
                        ['dashboard_callback_manager_index', 'dashboard_callback_manager_new'], 'icon-users',
                        false, null, null, 'sidebar.callback.callback_settings.callback_managers',
                        [], 'dashboard_callback_manager_index'
                    ),
                    self::itemSidebar(
                        ['ROLE_CALLBACK_MAIL_SETTING_EDIT'], [], ['dashboard_callback_mail_setting_edit'],
                        'icon-settings', false, null, null,
                        'sidebar.callback.callback_settings.callback_mail_setting', [],
                        'dashboard_callback_mail_setting_edit'
                    ),
                ], null)
        ], null);
    }
}