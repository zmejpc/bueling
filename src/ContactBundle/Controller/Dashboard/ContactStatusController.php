<?php

namespace ContactBundle\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use DashboardBundle\Controller\CRUDController;
use ContactBundle\Entity\ContactStatus;
use ContactBundle\Form\Type\Dashboard\ContactStatusType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ContactStatusController extends CRUDController
{
    /**
     * @param TranslatorInterface $translator
     * @return string
     */
    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.contacts.contacts', [], 'DashboardBundle') . ' > '
            . $this->translator->trans('sidebar.contacts.contact_settings.contact_settings', [], 'DashboardBundle') . ' > ' .
            $this->translator->trans('sidebar.contacts.contact_settings.contact_statuses', [], 'DashboardBundle');
    }

    public function getFormType(): string
    {
        return ContactStatusType::class;
    }

    public function getFormElement()
    {
        return new ContactStatus();
    }

    /**
     * @return array
     */
    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_CONTACT_STATUS_LIST', 'new' => 'ROLE_CONTACT_STATUS_CREATE',
            'edit' => 'ROLE_CONTACT_STATUS_EDIT', 'delete' => 'ROLE_CONTACT_STATUS_DELETE',
        ];
    }

    /**
     * @return array
     */
    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_contact_status_index', 'new' => 'dashboard_contact_status_new',
            'edit' => 'dashboard_contact_status_edit', 'delete' => 'dashboard_contact_status_delete',
            'ajax_delete_group' => 'dashboard_contact_status_ajax_delete_group', 
        ];
    }

    /**
     * @param EntityManagerInterface $em
     * @return \Doctrine\Common\Persistence\ObjectRepository|mixed
     */
    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(ContactStatus::class);

        return $repository;
    }

    /**
     * @return array
     */
    public function getConfigForIndexDashboard(): array
    {
        return [
            'pageLength' => 25,
            'lengthMenu' => '10, 20, 25, 50, 100, 150',
            'order_column' => 'position',
            'order_by' => "asc"
        ];
    }

    /**
     * @param TranslatorInterface $translator
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'translations-title' => $translator->trans('ui.title', [], 'DashboardBundle'),
            'position' => $translator->trans('ui.position', [], 'DashboardBundle'),
            'systemName' => $translator->trans('form.system_name', [], 'DashboardBundle'),
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
            'translations-title' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->translate()->getTitle(),
            ]),
            'position' => $item->getPosition(),
            'systemName' => $item->getSystemName(),
        ];
    }
}