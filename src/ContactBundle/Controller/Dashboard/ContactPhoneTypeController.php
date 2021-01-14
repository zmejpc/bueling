<?php

namespace ContactBundle\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use DashboardBundle\Controller\CRUDController;
use ContactBundle\Entity\ContactPhoneType;
use ContactBundle\Form\Type\Dashboard\ContactPhoneTypeType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class ContactPhoneTypeController extends CRUDController
{
    /**
     * @param TranslatorInterface $translator
     * @return string
     */
    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.contacts.contacts', [], 'DashboardBundle') . ' > '
            . $this->translator->trans('sidebar.contacts.contact_phones', [], 'DashboardBundle');
    }

    public function getFormType(): string
    {
        return ContactPhoneTypeType::class;
    }

    public function getFormElement()
    {
        return new ContactPhoneType();
    }

    /**
     * @return array
     */
    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_CONTACT_PHONE_LIST', 'new' => 'ROLE_CONTACT_PHONE_CREATE',
            'edit' => 'ROLE_CONTACT_PHONE_EDIT', 'delete' => 'ROLE_CONTACT_PHONE_DELETE',
        ];
    }

    /**
     * @return array
     */
    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_contact_phone_type_index', 'new' => 'dashboard_contact_phone_type_new',
            'edit' => 'dashboard_contact_phone_type_edit', 'delete' => 'dashboard_contact_phone_type_delete',
            'ajax_delete_group' => 'dashboard_contact_phone_type_ajax_delete_group', 
        ];
    }

    /**
     * @param EntityManagerInterface $em
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(ContactPhoneType::class);

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
            'contactPhones' => 'Телефоны',
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
            'contactPhones' => $twig->render('@Contact/dashboard/contact/list/phones.html.twig', [
                'elements' => $item->getContactPhones(),
            ]),
        ];
    }
}