<?php

namespace Ecommerce\Controller\Dashboard;

use DashboardBundle\Controller\CRUDController;
use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\OrderManager;
use Ecommerce\Form\Type\Dashboard\OrderManagerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
class OrderManagerController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.sales.orders.orders', [], 'DashboardBundle') . ' > '
            . $this->translator->trans('sidebar.sales.orders.orders_settings.orders_settings', [], 'DashboardBundle') . ' > ' .
            $this->translator->trans('sidebar.sales.orders.orders_settings.orders_managers', [], 'DashboardBundle');
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_ORDER_MANAGER_LIST', 'new' => 'ROLE_ORDER_MANAGER_CREATE',
            'edit' => 'ROLE_ORDER_MANAGER_EDIT', 'delete' => 'ROLE_ORDER_MANAGER_DELETE',
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_order_manager_index', 'new' => 'dashboard_order_manager_new',
            'edit' => 'dashboard_order_manager_edit', 'delete' => 'dashboard_order_manager_delete',
            'ajax_delete_group' => 'dashboard_order_manager_ajax_delete_group', 
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'icon-users';
    }

    public function getFormType(): string
    {
        return OrderManagerType::class;
    }

    public function getFormElement()
    {
        return new OrderManager();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(OrderManager::class);

        return $repository;
    }

    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'name' => $translator->trans('ui.first_name', [], 'DashboardBundle'),
            'email' => $translator->trans('ui.email', [], 'DashboardBundle'),
            'isSendForEmail' => $translator->trans('form.is_send_for_email', [], 'DashboardBundle'),
        ];
    }

    public function createDataForList($item, Environment $twig): array
    {
        return [
            'name' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->getName(),
            ]),
            'email' => $item->getEmail(),
            'isSendForEmail' => $twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item->getIsSendForEmail()
            ]),
        ];
    }
}