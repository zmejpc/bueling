<?php

namespace ContactBundle\Controller\Dashboard;

use ContactBundle\Entity\Contact;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait sidebarContactBundleTrait
{
    /**
     * @return array
     */
    private function ContactBundlePathForEdit(): array
    {
        return [
            'contact' => 'contact/edit',
            'status' => 'contact/status/edit',
            'manager' => 'contact/manager/edit',
            'setting' => 'contact/setting/mail',
            'phone_type' => 'contact/phone-type/edit'
        ];
    }

    /**
     * @return array
     */
    private function ContactBundleRouteName(): array
    {
        return [
            'contact' => [
                'index' => 'dashboard_contact_index'
            ],
            'status' => [
                'index' => 'dashboard_contact_status_index', 'new' => 'dashboard_contact_status_new'
            ],
            'manager' => [
                'index' => 'dashboard_contact_manager_index', 'new' => 'dashboard_contact_manager_new'
            ],
            'setting' => 'dashboard_contact_mail_setting',
            'phone_type' => [
                'index' => 'dashboard_contact_phone_type_index', 'new' => 'dashboard_contact_phone_type_new',
            ]
        ];
    }

    /**
     * @return array
     */
    private function ContactBundleRoles(): array
    {
        return [
            'contact' => 'ROLE_CONTACT',
            'status' => 'ROLE_CONTACT_STATUS_CREATE_EDIT',
            'manager' => 'ROLE_CONTACT_MANAGER_CREATE_EDIT',
            'phone_type' => 'ROLE_CONTACT_PHONE_CREATE_EDIT',
            'setting' => [
                'mail' => 'ROLE_CONTACT_MAIL_SETTING'
            ],
        ];
    }

    /**
     * @return array|null
     */
    private function sidebarContactBundle(): ?array
    {
        $countNewContactsRequests = $this->em->getRepository(Contact::class)->countNewContactRequests();

        $contacts = self::itemSidebar([
            self::contactBundleRoles()['contact'], self::contactBundleRoles()['status'], self::contactBundleRoles()['phone_type'],
            self::contactBundleRoles()['manager'], self::contactBundleRoles()['setting']['mail']
        ], [
            self::contactBundlePathForEdit()['contact'], self::contactBundlePathForEdit()['status'], self::contactBundlePathForEdit()['phone_type'],
            self::contactBundlePathForEdit()['manager'], self::contactBundlePathForEdit()['setting']
        ], [
            self::contactBundleRouteName()['contact']['index'], self::contactBundleRouteName()['status']['index'],
            self::contactBundleRouteName()['status']['new'], self::contactBundleRouteName()['manager']['index'],
            self::contactBundleRouteName()['manager']['new'], self::contactBundleRouteName()['setting'],
            self::contactBundleRouteName()['phone_type']['index'], self::contactBundleRouteName()['phone_type']['new']
        ], 'flaticon-multimedia-2', true, $countNewContactsRequests, '', 'sidebar.contacts.contacts',
            [], self::contactBundleRouteName()['contact']['index']);

        if (!is_null($contacts)) {

            $contact = self::itemSidebar([
                self::contactBundleRoles()['contact']
            ], [
                self::contactBundlePathForEdit()['contact']
            ], [
                self::contactBundleRouteName()['contact']['index']
            ], 'flaticon-envelope', true, $countNewContactsRequests, '',
                'sidebar.contacts.contact_form', [], self::contactBundleRouteName()['contact']['index']);

            (!is_null($contact)) ? $contacts['items'][] = $contact : null;

            $phoneType = self::itemSidebar([
                self::contactBundleRoles()['phone_type']
            ], [
                self::contactBundlePathForEdit()['phone_type']
            ], [
                self::contactBundleRouteName()['phone_type']['index'], self::contactBundleRouteName()['phone_type']['new']
            ], 'flaticon-support', false, null, null, 'sidebar.contacts.contact_phones',
                [], self::contactBundleRouteName()['phone_type']['index']);

            (!is_null($phoneType)) ? $contacts['items'][] = $phoneType : null;

            $settings = self::itemSidebar([
                self::contactBundleRoles()['status'],
                self::contactBundleRoles()['manager'],
                self::contactBundleRoles()['setting']['mail']
            ], [
                self::contactBundlePathForEdit()['status'],
                self::contactBundlePathForEdit()['manager'],
                self::contactBundlePathForEdit()['setting']
            ], [
                self::contactBundleRouteName()['status']['index'], self::contactBundleRouteName()['status']['new'],
                self::contactBundleRouteName()['manager']['index'], self::contactBundleRouteName()['manager']['new'],
                self::contactBundleRouteName()['setting'],
            ], 'flaticon-cogwheel', false, null, null,
                'sidebar.contacts.contact_settings.contact_settings', [], null);

            if (!is_null($settings)) {

                $status = self::itemSidebar([
                    self::contactBundleRoles()['status']
                ], [
                    self::contactBundlePathForEdit()['status']
                ], [
                    self::contactBundleRouteName()['status']['index'], self::contactBundleRouteName()['status']['new']
                ],
                    'flaticon-medal', false, null, null,
                    'sidebar.contacts.contact_settings.contact_statuses', [],
                    self::contactBundleRouteName()['status']['index']);

                (!is_null($status)) ? $settings['items'][] = $status : null;

                $manager = self::itemSidebar([
                    self::contactBundleRoles()['manager']
                ], [
                    self::contactBundlePathForEdit()['manager']
                ], [
                    self::contactBundleRouteName()['manager']['index'], self::contactBundleRouteName()['manager']['new']
                ],
                    'flaticon-users', false, null, null,
                    'sidebar.contacts.contact_settings.contact_managers', [],
                    self::contactBundleRouteName()['manager']['index']);

                (!is_null($manager)) ? $settings['items'][] = $manager : null;

                $mail = self::itemSidebar([
                    self::contactBundleRoles()['setting']['mail']
                ], [], [
                    self::contactBundleRouteName()['setting']
                ], 'flaticon-multimedia-2', false, null, null,
                    'sidebar.contacts.contact_settings.contact_mail_setting', [],
                    self::contactBundleRouteName()['setting']);

                (!is_null($mail)) ? $settings['items'][] = $mail : null;

                $contacts['items'][] = $settings;
            }

            return $contacts;
        }

        return null;
    }
}
