<?php

namespace ContactBundle\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use DashboardBundle\Controller\CRUDController;
use ContactBundle\Entity\ContactManager;
use ContactBundle\Form\Type\Dashboard\ContactManagerType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ContactManagerController extends CRUDController
{
    /**
     * @param TranslatorInterface $translator
     * @return string
     */
    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.contacts.contacts', [], 'DashboardBundle') . ' > '
            . $this->translator->trans('sidebar.contacts.contact_settings.contact_settings', [], 'DashboardBundle') . ' > ' .
            $this->translator->trans('sidebar.contacts.contact_settings.contact_managers', [], 'DashboardBundle');
    }

    public function getFormType(): string
    {
        return ContactManagerType::class;
    }

    public function getFormElement()
    {
        return new ContactManager();
    }

    /**
     * @return array
     */
    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_CONTACT_MANAGER_LIST', 'new' => 'ROLE_CONTACT_MANAGER_CREATE',
            'edit' => 'ROLE_CONTACT_MANAGER_EDIT', 'delete' => 'ROLE_CONTACT_MANAGER_DELETE',
        ];
    }

    /**
     * @return array
     */
    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_contact_manager_index', 'new' => 'dashboard_contact_manager_new',
            'edit' => 'dashboard_contact_manager_edit', 'delete' => 'dashboard_contact_manager_delete',
            'ajax_delete_group' => 'dashboard_contact_manager_ajax_delete_group', 
        ];
    }

    /**
     * @param EntityManagerInterface $em
     * @return \Doctrine\Common\Persistence\ObjectRepository|mixed
     */
    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(ContactManager::class);

        return $repository;
    }

    /**
     * @param TranslatorInterface $translator
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'name' => $translator->trans('ui.first_name', [], 'DashboardBundle'),
            'email' => $translator->trans('ui.email', [], 'DashboardBundle'),
            'isSendForEmail' => $translator->trans('form.is_send_for_email', [], 'DashboardBundle'),
        ];
    }

    /**
     * @param $item
     * @param Environment $twig
     * @return array
     */
    public function createDataForList($item, Environment $twig): array
    {
        return [
            'name' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->getName(),
            ]),
            'email' => $item->getEmail(),
            'isSendForEmail' => $item->getIsSendForEmail(),
        ];
    }
}