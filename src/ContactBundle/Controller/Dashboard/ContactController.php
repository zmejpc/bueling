<?php

namespace ContactBundle\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use ContactBundle\Entity\Contact;
use ContactBundle\Form\Type\Dashboard\ContactType;
use DashboardBundle\Controller\CRUDController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class ContactController extends CRUDController
{
    /**
     * @param TranslatorInterface $translator
     * @return string
     */
    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.contacts.contacts', [], 'DashboardBundle');
    }

    public function getFormType(): string
    {
        return ContactType::class;
    }

    public function getFormElement()
    {
        return new Contact();
    }

    /**
     * @return array
     */
    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_CONTACT_LIST', 'new' => null,
            'edit' => 'ROLE_CONTACT_EDIT', 'delete' => 'ROLE_CONTACT_DELETE',
        ];
    }

    /**
     * @return array
     */
    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_contact_index', 'new' => null,
            'edit' => 'dashboard_contact_edit', 'delete' => 'dashboard_contact_delete',
            'ajax_delete_group' => 'dashboard_contact_ajax_delete_group', 
        ];
    }

    /**
     * @param EntityManagerInterface $em
     * @return \Doctrine\Common\Persistence\ObjectRepository|\ContactBundle\Entity\Repository\ContactRepository
     */
    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Contact::class);

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
            'order_column' => 'id',
            'order_by' => "desc"
        ];
    }

    /**
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderNewContactWidget(EntityManagerInterface $em)
    {
        return $this->render('@SupportCenterContact/dashboard/contact/widget/_new_contact_widget.html.twig', [
            'value' => $this->getRepository($em)->countNewContactRequests()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderTableForHomepage()
    {
        return $this->render('@SupportCenterContact/dashboard/contact/homepage/table/_table.html.twig');
    }

    /**
     * @param TranslatorInterface $translator
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'name' => $translator->trans('ui.first_name', [], 'DashboardBundle'),
            'phone' => $translator->trans('ui.phone_number', [], 'DashboardBundle'),
            'email' => $translator->trans('ui.email', [], 'DashboardBundle'),
            'subject' => $translator->trans('ui.subject', [], 'DashboardBundle'),
            'status-title' => [
                'locked' => true,
                'title' => $translator->trans('ui.status', [], 'DashboardBundle'),
            ],
            'message' => $translator->trans('ui.message', [], 'DashboardBundle')
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
            'phone' => $item->getPhone(),
            'email' => $item->getEmail(),
            'subject' => $item->getSubject(),
            'status-title' => $item->getStatus(),
            'message' => $item->getMessage()
        ];
    }
}
